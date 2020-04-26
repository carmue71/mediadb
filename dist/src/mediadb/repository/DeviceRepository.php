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
    public $scanStatistics;
    public $errorFiles;
    
        public function __construct(PDO $pdo, EpisodeRepository $msr, FileRepository $fr)
    {
        \mediadb\Logger::debug("DeviceRepository.php: construction DeviceRepository");
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
            'errors' => ['unknownChannels'=>0, 'wrongPlacedFiles'=>0, 'DBErrors'=>0 ],
        ];
        
        $this->errorFiles = [];
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

    public function scan($device, bool $cmdline = false, $filesOnly = false, $episodesOnly = false, $episodeIDOnly = -1, $channelIDOnly = -1)
    {
        if ( !$cmdline )
            \mediadb\Logger::$consoleLevel = MDB_LOG_INFO;
        
        if (! $device->isActive()) {
            return [
                'result' => 'Error',
                'ErrorHeader' => "<h2>Problem with path {$device->Path}!</h2>",
                'ErrorMessage' => "<p>Either the path does not exist, is not a directory or you don't have sufficient rights to access. Please check!</p>" . "<br /><p>Scan aborted!</p><p align = centered>Return to <a href='" . INDEX . "devicelist'>Device-List</a></p>"
            ];
        }
        
        if ( !$cmdline ) 
            print "<pre>\nStarting Scan...\n";
        mediadb\Logger::info("DeviceRepository.php: Starting Scan...");
        mediadb\Logger::debug("DeviceRepository.php: Pfad {$device->Path} OK!");
        
        $result = scandir($device->Path . "files/");
        \mediadb\Logger::info("DeviceRepository.php: Found " . (count($result) - 2) . " potential channels.");
        
        foreach ($result as $dir) {
            if ($dir[0] == "."){
                continue; // skip .-Directories like ., .. and hidden ones.
            }
            if (! $device->isActive()) {
                return [
                    'result' => 'Error',
                    'ErrorHeader' => "<h2>Problem with path {$device->Path}! Scan cancelled!!!</h2>",
                    'ErrorMessage' => "<p>The device was removed while scanning. Please avoid this!</p>" . "<br /><p>Scan aborted!</p><p align = centered>Return to <a href='" . INDEX . "devicelist'>Device-List</a></p>"
                ];
            }
            \mediadb\Logger::debug("DeviceRepository.php: Checking {$dir}:");
            
            $id_channel = $this->findChannelByPath($dir);
            
            if (! isset($id_channel) || $id_channel < 0) {
                $this->scanStatistics['errors']['unknownChannels'];
                \mediadb\Logger::warning("DeviceRepository.php: Unknown Channel found: {$dir}");
                //TODO: remember device and channel
                continue;
            }
            
            if ( $channelIDOnly > -1 && $id_channel != $channelIDOnly ){
                \mediadb\Logger::debug("DeviceRepository.php: Skipping Channel");
                continue;
            }
            
            \mediadb\Logger::info("DeviceRepository.php: Channel identified {$id_channel}! Scanning for episodes: ");
            if (is_dir($device->Path . "/files/{$dir}")) {
                $sets = scandir($device->Path . "/files/{$dir}/");
                \mediadb\Logger::info("DeviceRepository.php: Found " . (count($sets) - 2)." potential episodes.");
                
                foreach ($sets as $set) {
                    if ($set[0] == ".")
                        continue; // skip .-Directories like ., .. and hidden ones.
                    
                    if (! $device->isActive()) {
                        \mediadb\Logger::warning("DeviceRepository.php: The device was removed while scanning - Scan cancelled");
                            return [
                                'result' => 'Error',
                                'ErrorHeader' => "<h2>Problem with path {$device->Path}! Scan cancelled!!!</h2>",
                                'ErrorMessage' => "<p>The device was removed while scanning. Please avoid this!</p>" . "<br /><p>Scan aborted!</p><p align = centered>Return to <a href='" . INDEX . "devicelist'>Device-List</a></p>"
                            ];
                    }
                        
                    $newSet = false;
                    
                    if ( !is_dir($device->Path . "/files/".$dir . "/" . $set ) ){
                            $this->scanStatistics['errors']['wrongPlacedFiles']++;
                            array_push($this->errorFiles,  $dir . "/" . $set);
                            \mediadb\Logger::warning("DeviceRepository.php: Episode expected, but file found: {$dir}/{$set}");
                        continue;
                    }
                    
                    $id_episode = $this->findEpisodeByPublisherCode($id_channel, $set);
                    
                    if (! isset($id_episode) || $id_episode < 0) {
                        $newSet = true;
                        if ( $filesOnly ){
                            \mediadb\Logger::debug("DeviceRepository.php: Ignoring new episode, because fileonly-option is set");
                            continue;
                        }
                        \mediadb\Logger::info("DeviceRepository.php: New Set found - adding to MediaDB");
                        $this->scanStatistics['episodes']['new']++;
                        $episode = new Episode();
                        $episode->ID_Episode = - 1;
                        $episode->Comment = "";
                        $episode->Keywords = "MediaDB-Scanner,MDS:" . date("Y-m-d") . ",";
                        $episode->Title = $set;
                        $episode->Link = "{$this->getDefaultPathSetFromChannel($id_channel)}{$set}";
                        $episode->REF_Channel = $id_channel;
                        $episode->PublisherCode = $set;
                        if ( !$this->episodeRepository->save($episode) || $episode->ID_Episode == -1 ) {
                            \mediadb\Logger::error("DeviceRepository.php: Episode not saved {$episode->Title}"); 
                            $this->scanStatistics['errors']['DBErrors']++;
                            continue;
                        }
                    } else {
                        $episode = $this->episodeRepository->find($id_episode);
                    }
                    if ( isset($episode) && $episode->ID_Episode > -1 ){
                        if ( $episodeIDOnly == -1 || $episodeIDOnly == $episode->ID_Episode && !$episodesOnly ){
                            $addedFiles = $this->findFiles($device, $episode, $dir . "/" . $set . "/","");
                            if ( $addedFiles > 0 ){
                                \mediadb\Logger::debug("DeviceRepository.php: Added {$addedFiles} Files");
                            }
                        }
                                
                    }
                    if ( ($newSet && $this->options['scan']['checkPoster'] == 1) || ($this->options['scan']['checkPoster'] == 2) ){
                        if ( $episode->Picture == "" && $this->setPoster($episode, $device) )
                            $this->episodeRepository->save($episode);
                    }
                    //TODO: Check Wallpaper, too
                }
            }
        }
        
        if ( !$cmdline )
            showStatistics(false);
        
    }
    
    public function showStatistics(bool $cmdline){
        
        \mediadb\Logger::info("DeviceRepository.php: Files added: {$this->scanStatistics['files']['new']}");
        \mediadb\Logger::info("DeviceRepository.php: Files updated: {$this->scanStatistics['files']['updated']}");
        \mediadb\Logger::info("DeviceRepository.php: Files removed: {$this->scanStatistics['files']['removed']}");
        \mediadb\Logger::info("DeviceRepository.php: Error - unknown channel: {$this->scanStatistics['errors']['unknownChannels']}");
        \mediadb\Logger::info("DeviceRepository.php: Error - miplaced file: {$this->scanStatistics['errors']['wrongPlacedFiles']}");
        \mediadb\Logger::info("DeviceRepository.php: Error - db error: {$this->scanStatistics['errors']['DBErrors']}");
        
        if ( !$cmdline ){
            print "</pre>";
            print "<h2>Scan Statistics</h2>\n";
            print "<p>Files added:   {$this->scanStatistics['files']['new']}\n</p>";
            print "<p>Files updated: {$this->scanStatistics['files']['updated']}\n</p>";
            print "<p>Files removed: {$this->scanStatistics['files']['removed']}\n</p>";
            print "<h3>Errors:</h3>";
            print("<p>Error - unknown channel: {$this->scanStatistics['errors']['unknownChannels']}</p>\n");
            print("<p>Error - miplaced file: {$this->scanStatistics['errors']['wrongPlacedFiles']}</p>\n");
            print("<p>Error - db error: {$this->scanStatistics['errors']['DBErrors']}</p>\n");
            print "<br/><p align = centered>Finished - return to <a href='".INDEX."'>MediaDB</a></p>";
        } else {
            print("Files added:   {$this->scanStatistics['files']['new']}\n");
            print("Files updated: {$this->scanStatistics['files']['updated']}\n");
            print("Files removed: {$this->scanStatistics['files']['removed']}\n\n");
            print("Error - unknown channel: {$this->scanStatistics['errors']['unknownChannels']}\n");
            print("Error - miplaced file: {$this->scanStatistics['errors']['wrongPlacedFiles']}\n");
            print("Error - db error: {$this->scanStatistics['errors']['DBErrors']}\n");
            
        }
    }
    
    private function setWallpaper($episode, $device){
        \mediadb\Logger::warning("DeviceRepository.php: feature setWallpaper not implemented yet!!!!");
    }
    
    private function setPoster($episode, $device){
        \mediadb\Logger::debug("DeviceRepository.php: No Poster set for {$episode->Title}");
        
        foreach ( ['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'WEBP','GIF'] as $ext ){
            $posterFile = ASSETSYSPATH."episodes/{$episode->PublisherCode}.{$ext}";
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
                \mediadb\Logger::info("DeviceRepository.php: Copyed {$firstPic} to {$posterFile} and linked");
                return true;
            } else {
                \mediadb\Logger::error("DeviceRepository.php: cannot copy {$firstPic} to {$posterFile}");
                return false;
            }
        } else {
            $posterFile = ASSETSYSPATH."episodes/{$episode->PublisherCode}.jpg";
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
        $cnt = count($filenames) - 2;
        \mediadb\Logger::debug("DeviceRepository.php: {$cnt} files found in {$path}");
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
                    $this->scanStatistics['files']['new']++;
                } else {
                    if ( $this->options['scan']['refreshFileInfo'] ){
                        \mediadb\Logger::debug("DeviceRepository.php: {$filename} already known - updating fileinfo!");
                        $this->scanStatistics['files']['updated']++;
                        $this->fileRepository->updateFileInfo($id_file, $currentPath . $filename);
                    } 
                }
            }
        }
        return $addedFiles; 
    }
    
    public function removeMissingFiles($device, $episodeID=-1, $channelID=-1){
        $fileCounter = 0;
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
        //check if channel or episode id is set
        $cntQry = "";
        if ( $episodeID > -1 ){
            $cntQry = "SELECT count(*) AS Number FROM File WHERE REF_Device = {$device->ID_Device} AND REF_Episode = {$episodeID}";
        } elseif ( $channelID > -1 ){
            $cntQry = "SELECT count(*) AS Number FROM File WHERE REF_Device = {$device->ID_Device} AND REF_Episode IN (SELECT ID_Episode FROM Episode WHERE REF_Channel = {$channelID})";
        } else {
            $cntQry = "SELECT count(*) AS Number FROM File WHERE REF_Device = {$device->ID_Device}";
        }
        $stmt = $this->pdo->prepare($cntQry);
        if ( $stmt->execute() ){
            $anzahl = $stmt->fetch()['Number'];
            \mediadb\Logger::info("DeviceRepository.php: Expecting {$anzahl} files on {$device->Name}.");
            $offset = 0;
            While ( $offset < $anzahl ){
                if ( $episodeID > -1 ){
                    $query = "SELECT ID_File, Name, Path FROM File WHERE REF_Device = {$device->ID_Device} AND REF_Episode = {$episodeID} LIMIT {$offset}, 10000";
                } elseif ( $channelID > -1 ){
                    $query = "SELECT ID_File, Name, Path FROM File WHERE REF_Device = {$device->ID_Device} AND REF_Episode IN (SELECT ID_Episode FROM Episode WHERE REF_Channel = {$channelID}) LIMIT {$offset}, 10000";
                } else {
                    $query = "SELECT ID_File, Name, Path FROM File WHERE REF_Device = {$device->ID_Device} LIMIT {$offset}, 10000";
                }
                $stmt = $this->pdo->prepare($query);
                if ( $stmt->execute() ) {
                    while ($file = $stmt->fetch()) {
                        if (! file_exists($baseDir . $file['Path'] . $file['Name'])) {
                            \mediadb\Logger::debug("DeviceRepository.php: removing ".$baseDir . $file['Path'] . $file['Name']." from db");
                            // remove file from database
                            $delStmt->execute(['fid' => $file['ID_File']]);
                            $fileCounter ++;
                            $this->scanStatistics['files']['removed']++;
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
    
    public function scanDevice($device, $ignoreExistingFiles, $filesOnly = false, $episodesOnly = false, $episodeID = - 1, $channelID = - 1)
    {
        if ($device->isActive()) {
            \mediadb\Logger::info("DeviceRepository.php: Scanning device {$device->Name}");

            if (! $ignoreExistingFiles && ! $episodesOnly) {
                \mediadb\Logger::info("DeviceRepository.php: Removing files");
                $this->removeMissingFiles($device, $episodeID, $channelID);
            }
            \mediadb\Logger::info("DeviceRepository.php: Scanning for directories and files");
            $this->scan($device, true, $filesOnly, $episodesOnly, $episodeID, $channelID);
        } else
            \mediadb\Logger::debug("DeviceRepository.php: Device {$device->Name} seems to be unavaillable - ignoring it for now.");
    }
}