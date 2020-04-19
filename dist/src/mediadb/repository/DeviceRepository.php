<?php

/* DeviceRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for device handling
 */

namespace mediadb\repository;

use PDO;
use PDOException;
use mediadb\model\Device;
use mediadb\model\Episode;


class DeviceRepository extends AbstractRepository
{
    public $options;
    
    private $episodeRepository;

    private $fileRepository;
    
        public function __construct(PDO $pdo, EpisodeRepository $msr, FileRepository $fr)
    {
        \mediadb\Logger::debug("DeviceRepository.php: construction DeviceRepository");
        $this->logLevel = 1;
        parent::__construct($pdo);
        $this->filter = "";
        $this->className = "\\mediadb\\model\\Device";
        $this->tableName = "Device";
        $this->orderBy = "Name";
        $this->idColumn = "ID_Device";
        $this->episodeRepository = $msr;
        $this->fileRepository = $fr;
        $this->options = [
            'scan'=>[
                'refreshFileInfo'=>false, 
                'checkPoster' => 1,
                'checkWallpaper' => 1,
            ] 
        ];
        
        $this->scanStatistics =[
            'devices' => 0,
            'episodes' => [                'new' => 0,                'updated' => 0            ],
            'files' => [ 'new' =>0, 'updated' => 0, 'removed'=>'0'],
            'errors' => ['unknownChannels'=>0, 'wrongPlacedFiles'=>0],
        ];
        
        $this->errorLog = "";
        $this->options['scan']['refreshFileInfo'] = false;
    }

    public function save($device)
    {
        $result = false;
        
        if ($device->ID_Device == - 1) { // New Device
            $query = "INSERT INTO Device (Name, Path, DisplayPath, Comment) VALUES (:name, :path, :dpath, :comment)";
            $params = [
                'name' => $device->Name,
                'path' => $device->Path,
                'dpath' => $device->DisplayPath,
                'comment' => $device->Comment
            ];
        } else {
            $query = "UPDATE Device SET Name = :name, PATH = :path, DisplayPath = :dpath, Comment = :comment WHERE ID_Device = :id";
            $params = [
                'name' => $device->Name,
                'path' => $device->Path,
                'dpath' => $device->DisplayPath,
                'comment' => $device->Comment,
                'id' => $device->ID_Device
            ];
        }
        try {
            $stmt = $this->pdo->prepare($query);
            if ($stmt) {
                $result = $stmt->execute($params);
                if ($result) {
                    return true;
                }
                print "<br><b>save</b>: result not ok:<br>";
                var_dump($result);                
            }
            print "<br><b>save</b>: No Statement!:<br>";
            var_dump($stmt);
            var_dump($device);
            print "<br/>";
            var_dump($query);
            print "<br/>";
            var_dump($params);
            print "<br/>";
            var_dump($this->pdo->errorInfo());
            print "<br/>";
            return false;
        } catch (PDOException $e) {
            return "Failed: " . $e->getMessage() . "\n";
        }
    }

    private function findChannelByPath(string $path)
    {
        $query = "SELECT ID_Channel from Channel WHERE Site = :path";
        $stmt = $stmt = $this->pdo->prepare($query);
        if ($stmt->execute([
            'path' => strtolower($path)
        ])) {
            $id = $stmt->fetch();
            return $id['ID_Channel'];
        } else {
            print "<pre>";
            var_dump($query);
            var_dump($this->pdo->errorInfo());
            print "</pre>";
            return - 1;
        }
    }

    private function findEpisodeByPublisherCode(int $id_channel, string $code)
    {
        // TODO: ignore case and leading zeros
        $query = "SELECT ID_Episode from Episode WHERE REF_Channel = :id_channel AND PublisherCode = :code";
        $stmt = $stmt = $this->pdo->prepare($query);
        if ($stmt->execute([
            'id_channel' => $id_channel,
            'code' => strtolower($code)
        ])) {
            $id = $stmt->fetch();
            if ($id == null)
                return - 1;
            return $id['ID_Episode'];
        } else {
            print "<pre>";
            var_dump($query);
            var_dump($this->pdo->errorInfo());
            print "</pre>";
            return - 1;
        }
    }

    private function warning(String $msg)
    {
        ?>
	<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?php print $msg;?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<?php
    }

    public function scan($device, bool $cmdline = false, int $logLevel=1, $filesOnly = false, $episodesOnly = false, $episodeIDOnly = -1, $channelIDOnly = -1)
    {
        $this->logLevel = $logLevel;
        if (! $device->isActive()) {
            return [
                'result' => 'Error',
                'ErrorHeader' => "<h2>Problem with path {$device->Path}!</h2>",
                'ErrorMessage' => "<p>Either the path does not exist, is not a directory or you don't have sufficient rights to access. Please check!</p>" . "<br /><p>Scan aborted!</p><p align = centered>Return to <a href='" . INDEX . "devicelist'>Device-List</a></p>"
            ];
        }
        
        if ( !$cmdline ) 
            print "<pre>\n";
        if ( $this->logLevel > 0 )
            print "Starting Scan...\n\n";
        
        print "Pfad {$device->Path} OK!\n";
        
        $result = scandir($device->Path . "files/");
        print "\nFound " . (count($result) - 2) . " potential channels.\n";
        
        foreach ($result as $dir) {
            if ($dir[0] == ".")
                continue; // skip .-Directories like ., .. and hidden ones.
            
                if (! $device->isActive()) {
                    return [
                        'result' => 'Error',
                        'ErrorHeader' => "<h2>Problem with path {$device->Path}! Scan cancelled!!!</h2>",
                        'ErrorMessage' => "<p>The device was removed while scanning. Please avoid this!</p>" . "<br /><p>Scan aborted!</p><p align = centered>Return to <a href='" . INDEX . "devicelist'>Device-List</a></p>"
                ];
                }
                
            print "\n\tChecking {$dir}:";
            
            $id_channel = $this->findChannelByPath($dir);
            
            if (! isset($id_channel) || $id_channel < 0) {
                $this->warning("\n<strong>*************************************************\nError: Unknown Channel </strong> found: <i>$dir</i>\n*************************************************\n");
                
                //TODO: update error counter and log
                continue;
            }
            
            if ( $channelIDOnly > -1 && $id_channel != $channelIDOnly ){
                print("\t\t\tSkipping Channel\n");
                //wrong channel
                continue;
            }
            
            print "\n\t\tChannel identified {$id_channel}! \n\t\tScanning for episodes: ";
            if (is_dir($device->Path . "/files/{$dir}")) {
                $sets = scandir($device->Path . "/files/{$dir}/");
                print "\n\t\tFound " . (count($sets) - 2)." directories - i.e. potential episodes.";
                
                foreach ($sets as $set) {
                    if ($set[0] == ".")
                        continue; // skip .-Directories like ., .. and hidden ones.
                    
                    if (! $device->isActive()) {
                            return [
                                'result' => 'Error',
                                'ErrorHeader' => "<h2>Problem with path {$device->Path}! Scan cancelled!!!</h2>",
                                'ErrorMessage' => "<p>The device was removed while scanning. Please avoid this!</p>" . "<br /><p>Scan aborted!</p><p align = centered>Return to <a href='" . INDEX . "devicelist'>Device-List</a></p>"
                            ];
                    }
                        
                    $newSet = false;
                    //print "<li>Checking <i>{$set}</i>: <br/>";
                    
                        if ( !is_dir($device->Path . "/files/".$dir . "/" . $set ) ){
                        print("\n\n *********************************************************\nError: Directory (Episode) expected, but file found;\n please check ".$dir . "/" . $set."!\n***********\n\n");
                        continue;
                    }
                    
                    $id_episode = $this->findEpisodeByPublisherCode($id_channel, $set);
                    
                    if (! isset($id_episode) || $id_episode < 0) {
                        $newSet = true;
                        if ( $filesOnly ){
                            print("\n\t\t\tIgnoring new episode, because fileonly-option is set\n");
                            continue;
                        }
                        print "\n\t\t\tNew Set found - adding to MediaDB\n";
                        $episode = new Episode();
                        $episode->ID_Episode = - 1;
                        $episode->Comment = "";//Automatically added by Scanner";
                        $episode->Keywords = "MediaDB-Scanner,MDS:" . date("Y-m-d") . ",";
                        $episode->Title = $set;
                        $episode->Link = "{$this->getDefaultPathSetFromChannel($id_channel)}{$set}";
                        $episode->REF_Channel = $id_channel;
                        $episode->PublisherCode = $set;
                        if ( !$this->episodeRepository->save($episode) || $episode->ID_Episode == -1 ) {
                            print "\n\n***** Episode not saved!";
                            var_dump($episode);
                            continue;
                        }
                    } else {
                        $episode = $this->episodeRepository->find($id_episode);
                    }
                    if ( isset($episode) && $episode->ID_Episode > -1 ){
                        if ( $episodeIDOnly == -1 || $episodeIDOnly == $episode->ID_Episode && !$episodesOnly ){
                            $addedFiles = $this->findFiles($device, $episode, $dir . "/" . $set . "/","");
                            if ( $addedFiles > 0 )
                                print "\n\t\t\t\tAdded {$addedFiles} Files";
                        }
                                
                    }
                    if ( ($newSet && $this->options['scan']['checkPoster'] == 1) || ($this->options['scan']['checkPoster'] == 2) ){
                        if ( $episode->Picture == "" && $this->setPoster($episode, $device) )
                            $this->episodeRepository->save($episode);
                    }
                }
            }
        }
        if ( !$cmdline ){
            print "</pre>";
            print "<p align = centered>Finished - return to <a href='".INDEX."'>MediaDB</a></p>";
        }
    }
    
    private function setWallpaper($episode, $device){
        \mediadb\Logger::warning("DeviceRepository.php: feature setWallpaper not implemented yet!!!!");
    }
    
    private function setPoster($episode, $device){
        \mediadb\Logger::debug("DeviceRepository.php: No Poster set for {$episode->Title}");
        
        foreach ( ['jpg', 'jpeg', 'png', 'webp', 'gif', 'JPG', 'JPEG', 'PNG', 'WEBP','GIF'] as $ext ){
            $posterFile = ASSETSYSPATH."episodes/{$episode->PublisherCode}{$ext}";
            if ( file_exists($posterFile) ){
                \mediadb\Logger::info("DeviceRepository.php: Found poster {$posterFile}; linking.");
                $episode->Picture = $episode->PublisherCode.".".$ext;
                return true;
            }
        }
        $firstPic = $this->fileRepository->findFirstPictureOnDevice($episode->ID_Episode, $device);
        if ( $firstPic != "" && file_exists($firstPic )){
            \mediadb\Logger::debug("DeviceRepository.php: tUsing the first picture: ".$firstPic);
            $ext = pathinfo($firstPic, PATHINFO_EXTENSION);
            $posterFile = ASSETSYSPATH."episodes/{$episode->PublisherCode}.{$ext}";
            //TODO: resize the image if it is too large
            if ( copy($firstPic, $posterFile) ){
                $episode->Picture = $episode->PublisherCode.".".$ext;
                \mediadb\Logger::info("DeviceRepository.php: Copyed {$firstPic} to {$poserFiler} and linked");
                return true;
            } else {
                \mediadb\Logger::error("DeviceRepository.php: cannot copy {$firstPic} to {$poserFiler}");
                return false;
            }
        } else {
            \mediadb\Logger::info("DeviceRepository.php: Found no suitable file");
            if ( $this->fileRepository->getImageFromVideo($episode->ID_Episode, $device, $posterFile)){
                $episode->Picture = $episode->PublisherCode.".jpg";
                return true;
            }
        }
        return false;
    }
    
    private function findFiles(Device $device, Episode $episode, string $path, string $title)
    {
        $addedFiles = 0;
        $currentPath = $device->Path . "/files/" . $path;
        $filenames = scandir($currentPath);
        \mediadb\Logger::info("DeviceRepository.php:  {(count($filenames) - 2)} files found in {$path}");
        foreach ($filenames as $filename) {
            if ($filename[0] == ".")
                continue; // skip .-Directories like ., .. and hidden ones.
            //check if file is a directory
            if (is_dir($currentPath . $filename)) {
                //set the foldername as title
                $addedFiles+=$this->findfiles($device, $episode, $path . $filename . "/", $filename);
            } else {
                $id_file = $this->fileRepository->findFile($device->ID_Device, $episode->ID_Episode, $filename, $path);
                if ($id_file < 0) { // not in the database, so add the file
                    $file = $this->fileRepository->createFile($device, $episode->ID_Episode, $filename, $path, $title);
                    \mediadb\Logger::debug("DeviceRepository.php: {$filename} added to db!");
                    $this->fileRepository->addFile($file);
                    $addedFiles++;
                    $deviceRepository->scanStatistics['files']['new']++;
                } else {
                    if ( $this->options['scan']['refreshFileInfo'] ){
                        \mediadb\Logger::info("DeviceRepository.php: {$filename} already known - updating fileinfo!");
                        $this->fileRepository->updateFileInfo($id_file, $currentPath . $filename);
                    }
                    // TODO: Set availlable = true
                }
            }
        }
        return $addedFiles; 
    }
    
    public function removeMissingFiles($device, $logLevel, $episodeID=-1, $channelID=-1){
        $fileCounter = 0;
        $this->logLevel = $logLevel;
        if ( !$device->isActive() )
            return;
        
        //prepare delete query
        $delQuery = "DELETE FROM File WHERE ID_File = :fid LIMIT 1";
        $delStmt = $this->pdo->prepare($delQuery);
        if ( !$delStmt ){
            \mediadb\Logger::error("DeviceRepository.php: Error {$this->pdo->errorCode()} while executing: {$delQuery}");
            \mediadb\Logger::error("DeviceRepository.php: \tError-Info: {$this->pdo->errorInfo()}");
            return -1;
        }
            
        $baseDir = $device->Path."files/";
        if ( !file_exists($baseDir) || !is_dir($baseDir)){
            \mediadb\Logger::error("DeviceRepository.php: Device {$device->Name} seems to have a problem!");
            return -2;
        }
        $cntQry = "SELECT count(*) AS Number FROM File WHERE REF_Device = {$device->ID_Device}";
        $stmt = $this->pdo->prepare($cntQry);
        if ( $stmt->execute() ){
            $anzahl = $stmt->fetch()['Number'];
            \mediadb\Logger::info("DeviceRepository.php: Expecting {$anzahl} files on {$device->Name}.");
            $offset = 0;
            While ( $offset < $anzahl ){
                $query = "SELECT ID_File, Name, Path FROM File WHERE REF_Device = {$device->ID_Device} LIMIT {$offset}, 10000";
                $stmt = $this->pdo->prepare($query);
                if ($stmt->execute()) {
                    while ($file = $stmt->fetch()) {
                        if (! file_exists($baseDir . $file['Path'] . $file['Name'])) {
                            \mediadb\Logger::debug("DeviceRepository.php: removing ".$baseDir . $file['Path'] . $file['Name']." from db");
                            // remove file from database
                            $delStmt->execute(['fid' => $file['ID_File']]);
                            $fileCounter ++;
                            $deviceRepository->scanStatistics['files']['removed']++;
                        }
                    }
                }
                $offset +=10000;
            }
        }
        return $fileCounter;
    }
    
    private function getDefaultPathSetFromChannel(int $id_channel){
        $query = "SELECT DefaultSetPath FROM Channel WHERE ID_Channel = {$id_channel} LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute()) {
            if ( $path = $stmt->fetch() ) {
                return $path['DefaultSetPath'];
            }
        }
        return DEFAULT_SET_PATH; 
    }
    
    public function scanDevice($device, $ignoreExistingFiles, $loglevel, $filesOnly = false, $episodesOnly = false, $episodeID = - 1, $channelID = - 1)
    {
        if (isset($device)) {
            if ($device->isActive()) {
                \mediadb\Logger::info("DeviceRepository.php: Scanning device {$device->Name}");

                if (! $ignoreExistingFiles && ! $episodesOnly ) {
                    \mediadb\Logger::info("DeviceRepository.php: Removing files");
                    $this->removeMissingFiles($device, $loglevel, $episodeID, $channelID);
                }
                \mediadb\Logger::info("DeviceRepository.php: Scanning for directories and files");
                $this->scan($device, true, $loglevel, $filesOnly, $episodesOnly, $episodeID, $channelID);
            } else
                \mediadb\Logger::info("DeviceRepository.php: Device {$device->Name} seems to be unavaillable - ignoring it for now.");
        }
    }
}