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

//define('LIB_PATH', '/home/torsten/projects/');

//TODO: include scan_options.php;
class DeviceRepository extends AbstractRepository
{
    public $options;
    
    private $episodeRepository;

    private $fileRepository;
    
        public function __construct(PDO $pdo, EpisodeRepository $msr, FileRepository $fr)
    {
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
                'checkPoster' => false,
                'setPosterForNewSets' => true,
            ] 
        ];
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

    public function scan($device, bool $cmdline = false, int $logLevel=1)
    {
        $this->logLevel = 2;
        //$this->logLevel = $logLevel;
        if (! $device->isActive()) {
            return [
                'result' => 'Error',
                'ErrorHeader' => "<h2>Problem with path {$device->Path}!</h2>",
                'ErrorMessage' => "<p>Either the path does not exist, is not a directory or you don't have sufficient rights to access. Please check!</p>" . "<br /><p>Scan aborted!</p><p align = centered>Return to <a href='" . INDEX . "devicelist'>Device-List</a></p>"
            ];
        }
        
        if ( !$cmdline ) 
            print "<pre>\n";
        
        print "Starting Scan...\n\n";
        // TODO: Set availlable = false f.a. files of this device
        
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
                        //print "Searching files: ";
                        $addedFiles = $this->findFiles($device, $episode, $dir . "/" . $set . "/","");
                        if ( $addedFiles > 0 )
                            print "\n\t\t\t\tAdded {$addedFiles} Files";
                    }
                    if ( ( $newSet && $this->options['scan']['setPosterForNewSets']) || ($this->options['scan']['checkPoster']) ){
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
    
    private function setPoster($episode, $device){
        if ( $this->logLevel > 1 ) print "\n\t\t\t\tNo Poster set for {$episode->Title} - checking ...";
            
        //TODO: Check other filetypes as wellmou
        if ( file_exists(ASSETSYSPATH."episodes/{$episode->PublisherCode}.jpg") ){
            if ( $this->logLevel > 1 ) print "\n\t\t\t\t\tFound matching asset - linking ...";
            $episode->Picture = $episode->PublisherCode.".jpg";
            return true;
        } else {
            $firstPic = $this->fileRepository->findFirstPictureOnDevice($episode->ID_Episode, $device);
            
            if ( $firstPic != "" && file_exists($firstPic )){
                if ( $this->logLevel > 1 ) print ("\n\t\t\t\t\tUsing the first picture: ".$firstPic);
                //print "target: ".ASSETSYSPATH."episodes/{$episode->PublisherCode}.jpg";
                //TODO: resize the image if it is too large
                if ( copy ($firstPic, ASSETSYSPATH."episodes/{$episode->PublisherCode}.jpg") ){
                    $episode->Picture = $episode->PublisherCode.".jpg";
                    return true;
                } else {
                    print "********************************************************\n".
                          " Error: Cannot copy $firstPic to ". ASSETSYSPATH."episodes/{$episode->PublisherCode}.jpg\n".
                          "********************************************************\n";
                    return false;
                }
            } else {
                if ( $this->logLevel > 1 && $firstPic <> "" ){
                    print ("\nWarning: firstPic can't be set as poster: '{$firstPic}'");
                }
                $this->fileRepository->getImageFromVideo($episode->ID_Episode, $device);
            }
        }
        return false;
    }
    
    private function findFiles(Device $device, Episode $episode, string $path, string $title)
    {
        $addedFiles = 0;
        $currentPath = $device->Path . "/files/" . $path;
        $filenames = scandir($currentPath);
        if ( $this->logLevel > 1 )
            print ("\n\t\t\t\t".(count($filenames) - 2) . " files found in {$path}");
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
                    if ( $this->logLevel > 1) print "\n\t\t\t\t{$filename} seems new - adding it!";
                    $this->fileRepository->addFile($file);
                    $addedFiles++;
                } else {
                    if ( $this->options['scan']['refreshFileInfo'] ){
                        if ( $this->logLevel > 1 ) print "\n\t\t\t\t{$filename} already known - updating fileinfo!";
                        $this->fileRepository->updateFileInfo($id_file, $currentPath . $filename);
                    }
                    // TODO: Set availlable = true
                }
            }
        }
        return $addedFiles; 
    }
    
    public function removeMissingFiles($device, $logLevel){
        $fileCounter = 0;
        $this->logLevel = $logLevel;
        if ( !$device->isActive() )
            return;
        
        //prepare delete query
        $delQuery = "DELETE FROM File WHERE ID_File = :fid LIMIT 1";
        $delStmt = $this->pdo->prepare($delQuery);
        if ( !$delStmt ){
            var_dump($delQuery);
            var_dump($this->pdo->errorInfo());
            var_dump($this->pdo->errorCode());
            return -1;
        }
            
        $baseDir = $device->Path."files/";
        if ( !file_exists($baseDir) || !is_dir($baseDir)){
            print "\n\nDevice seems to have Problems!";
            return;
        }
        $cntQry = "SELECT count(*) AS Number FROM File WHERE REF_Device = {$device->ID_Device}";
        $stmt = $this->pdo->prepare($cntQry);
        if ( $stmt->execute() ){
            $anzahl = $stmt->fetch()['Number'];
            print "Found $anzahl files\n";
            $offset = 0;
            While ( $offset < $anzahl ){
                $query = "SELECT ID_File, Name, Path FROM File WHERE REF_Device = {$device->ID_Device} LIMIT {$offset}, 10000";
                $stmt = $this->pdo->prepare($query);
                if ($stmt->execute()) {
                    while ($file = $stmt->fetch()) {
                        if (! file_exists($baseDir . $file['Path'] . $file['Name'])) {
                            // remove file from database
                            $delStmt->execute(['fid' => $file['ID_File']]);
                            $fileCounter ++;
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
}