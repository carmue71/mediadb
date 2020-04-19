<?php

/* DeviceRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for file handling
 * 3rd Party Product used: getID3, https://www.getid3.org/ 
 */

namespace mediadb\repository;

use mediadb\model\Device;
use mediadb\model\File;
include_once(SRC_PATH.'lib/getid3/getid3.php');

class FileRepository extends AbstractRepository
{
    
    private $getID3;
    
    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
        \mediadb\Logger::debug("FileRepository.php: loaded");
        $this->filter = "";
        $this->className = "\\mediadb\\model\\File";
        $this->tableName = "File";
        $this->orderBy = "Name";
        $this->idColumn = "ID_File";
        
        $this->getID3 = new \getID3;
    }
    
    public function createFile(Device $device, $idEpisode, $filename, $path, $title){
        $file = new File();
        $file->REF_Device = $device->ID_Device;
        $file->REF_Episode = $idEpisode;
        $file->Name = $filename;
        $file->Path = $path;
        try {
            $file->REF_Filetype = $this->getFiletype($filename);
            $this->extractFileInfo($file, $device->Path.'files/'.$path.$filename);
        } catch (\Excpetion $error){
            print($error);
        }
        
        return $file;
    }
        
    private function extractFileInfo($file, $fullname){
        try {
            $file->Created = date("Y-m-d H:i:s", filectime($fullname));
            $file->Modified = date("Y-m-d H:i:s", filemtime($fullname));

            $fileExt = strtolower(pathinfo($fullname, PATHINFO_EXTENSION));
            $validExt = array(
                'jpg',
                'jpeg',
                'png',
                'mp3',
                'mp4',
                'm4v',
                'avi',
                'webm',
                'wmv',
                'mkv'
            );

            if (in_array($fileExt, $validExt)) {
                try {
                    $fileInfo = $this->getID3->analyze($fullname);
                    \getid3_lib::CopyTagsToComments($fileInfo);

                    $file->Size = $fileInfo['filesize'];

                    if (isset($fileInfo['video']) && isset($fileInfo['video']['resolution_x']))
                        $file->ResX = $fileInfo['video']['resolution_x'];

                    if (isset($fileInfo['video']) && isset($fileInfo['video']['resolution_y']))
                        $file->ResY = $fileInfo['video']['resolution_y'];

                    if (isset($fileInfo['playtime_seconds']))
                        $file->Playtime = $fileInfo['playtime_seconds'];

                    $info = "";
                    if (isset($fileInfo['fileformat']))
                        $info = "Fileformat:{$fileInfo['fileformat']}\n";

                    if (isset($fileInfo['playtime_string']))
                        $info = $info . "Playtime:{$fileInfo['playtime_string']}\n";

                    if (isset($fileInfo['comments_html']) && isset($fileInfo['comments_html']['title'])) {
                        // var_dump($fileInfo['comments_html']['title']);
                        $info = $info . "Title:\t{$fileInfo['comments_html']['title'][0]}\n";
                    }
                    // TODO: add further information
                    $file->Info = $info;
                    return;
                } catch (\Exception $ex) {
                    var_dump($ex);
                }
            }
        } catch (\Exception $generror) {
            var_dump($generror);
        }
        $file->Info = null;
    }
    
    public function isVideo($ext){
        return $ext == "mp4" || $ext == "m4v" || $ext =='webm' || $ext=='avi' || $ext =='wmv' || $ext =="mkv";
    }
    
    public function isImage($ext){
        return $ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif" || $ext== "webp";
    }
        
    private function getFiletype($file){
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ( $this->isVideo($ext) ){
            #print("\nVideo: ".$file);
            return 3;
        }
        if ( $this->isImage($ext) ){
                #print("\nImage: ".$file);
                return 2; 
        }
        #print("\nother: ".$file);
        return 7;
    }
    
    public function updateFileInfo(int $fid, String $fullname) {
        try {
            $file = new File();
            $file->ID_File = $fid;
            $this->extractFileInfo($file, $fullname);

            $query = "UPDATE File SET FileInfo = :info, Size =:size, ResX=:resx, ResY=:resy, Created = :created, " . "Modified =:modified, Playtime =:playtime  WHERE ID_File = :fid LIMIT 1";

            $parameters = [
                'info' => $file->Info,
                'size' => $file->Size,
                'resx' => $file->ResX,
                'resy' => $file->ResY,
                'created' => $file->Created,
                'modified' => $file->Modified,
                'playtime' => $file->Playtime,
                'fid' => $fid
            ];
            $this->execute($query, $parameters);
        } catch (\Exception $generror) {
            var_dump($generror);
        }
    }
       
    public function findFile(int $id_device, int $id_episode, String $filename, String $path)
    {
        $query = "SELECT {$this->idColumn} FROM {$this->tableName} WHERE REF_Device = :id_device and REF_Episode = :id_episode and Name = :name and Path=:path";
        $parameter = array(
            'id_device' => $id_device,
            'id_episode' => $id_episode,
            'name' => $filename,
            'path' => $path
        );
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute($parameter)) {
            $val = $stmt->fetch();
            if ($val != null)
                return $val['ID_File'];
            else
                return -1;
        }
        \mediadb\Logger::error("FileRepository.php: error finding file: {$query}");
        return -1;
    }
    
    public function addFile(File $file){
        try {
            $query = "INSERT INTO {$this->tableName} (REF_Device, REF_Episode, REF_Filetype, Name, Path, Title, FileInfo, Size, ResX, ResY, Created, Modified, Playtime )" . "VALUES (:id_device, :id_episode, :ref_filetype, :name, :path, :title, :info, :size, :resx, :resy, :created, :modified, :playtime)";
            $parameters = array(
                'id_device' => $file->REF_Device,
                'id_episode' => $file->REF_Episode,
                'ref_filetype' => $file->REF_Filetype,
                'name' => $file->Name,
                'path' => $file->Path,
                'title' => $file->Title,
                'info' => $file->FileInfo,
                'size' => $file->Size,
                'resx' => $file->ResX,
                'resy' => $file->ResY,
                'created' => $file->Created,
                'modified' => $file->Modified,

                'playtime' => $file->Playtime
            );
            return $this->execute($query, $parameters);
        } catch (\Exception $generror) {
            var_dump($generror);
            return null;
        }
    }
    
    // ----- file handling ---------------------------------------------------------------
    public function findFilesForEpisode(int $id_episode, int $filetype = -1, int $page=0, int $pageSize=0){
        $this->tableName = "V_FileWithDevice";
        $filter = ( $filetype > -1 ) ?
            " REF_Episode = {$id_episode} AND REF_Filetype = {$filetype}": " REF_Episode = {$id_episode}";
        $order="Name";
        return $this->getAll($pageSize, ($page-1)*$pageSize, $filter, $order);
    }
    
    public function findFiles(int $id_episode, String $sqlFileFilter, String $fileOrder, int $fileOffset, int $filePageSize){
        $this->tableName = "V_FileWithDevice";
        $filter = "REF_Episode={$id_episode}";
        
        if ( $sqlFileFilter != "" ){
            $filter = $filter." AND ".$sqlFileFilter;
        }
        return $this->getAll($filePageSize, $fileOffset, $filter, $fileOrder);
    }
    
    public function findFilesForActor(int $id_actor, String $sqlFileFilter, String $fileOrder, int $fileOffset, int $filePageSize){
        $this->tableName = "V_FileWithDevice";
        $filter = "REF_Episode IN (SELECT REF_Episode FROM C_Actor_Episode WHERE REF_Actor = {$id_actor}) ";
        
        if ( $sqlFileFilter != "" ){
            $filter = $filter." AND ".$sqlFileFilter;
        }
        return $this->getAll($filePageSize, $fileOffset, $filter, $fileOrder);
    }
    
    public function findFilesForChannel(int $id_channel, String $sqlFileFilter, String $fileOrder, int $fileOffset, int $filePageSize){
        $this->tableName = "V_FileWithDevice";
        $filter = "REF_Episode IN (SELECT ID_Episode FROM Episode WHERE REF_Channel = {$id_channel}) ";
        
        if ( $sqlFileFilter != "" ){
            $filter = $filter." AND ".$sqlFileFilter;
        }
        return $this->getAll($filePageSize, $fileOffset, $filter, $fileOrder);
    }
    
    public function countFilesForEpisode($ID_Episode, $filter){
        $query = "SELECT COUNT(*) as Number from File WHERE REF_Episode = :id_ms ";
        if ( $filter != "" )
            $query = $query . " AND ".$filter;
        $paramters = array(
            'id_ms' => $ID_Episode
        );
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute($paramters)) {
            if ($stmt != null)
                return $stmt->fetch()['Number'];
            else
                return 0;
        } else {
            \mediadb\Logger::error("FileRepository.php: (3) error {$this->pdo->errorInfo()} while executing {$query}");
            return 0;
        }
    }
    
    public function countFilesForActor($actorid, $filter){
        $query = "SELECT COUNT(*) as Number from File WHERE REF_Episode IN (SELECT REF_Episode FROM C_Actor_Episode WHERE REF_Actor = :id_actor) ";
        if ( $filter != "" )
            $query = $query . " AND ".$filter;
        $paramters = array('id_actor' => $actorid);
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute($paramters)) {
            if ($stmt != null)
                return $stmt->fetch()['Number'];
                else
                    return 0;
        } else {
            \mediadb\Logger::error("FileRepository.php: (1) error {$this->pdo->errorInfo()} while executing {$query}");
            return 0; 
        }
    }
    
    public function countFilesForChannel($channelid, $filter=""){
        $query = "SELECT COUNT(*) as Number from File WHERE REF_Episode IN (SELECT ID_Episode FROM Episode WHERE REF_Channel = :id_channel) ";
        if ($filter != "") {
            $query = $query . " AND " . $filter;
        }
        $paramters = array(
            'id_channel' => $channelid
        );
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute($paramters)) {
            if ($stmt != null)
                return $stmt->fetch()['Number'];
            else
                return 0;
        } else {
            \mediadb\Logger::error("FileRepository.php: (2) error {$this->pdo->errorInfo()} while executing {$query}");
            return 0;
        }
    }
    
    /*
     * Looks for the first Pic on a given device to set as poster
     */
    public function findFirstPictureOnDevice(int $idEpisode, $device){
        //search for the first picture
        $query = "SELECT * FROM V_FileWithDevice WHERE REF_Episode = :idms AND REF_Device = :idd AND REF_Filetype = 2 LIMIT 1";
        $parameters = array('idms'=>$idEpisode, 'idd'=>$device->ID_Device);
        $file = $this->queryFirst($query, $parameters, $this->className);
        if ( $file ){
            \mediadb\Logger::debug("FileRepository.php: Found file {$file->Name}");
            $fullpath = "{$file->SystemPath}files/{$file->Path}{$file->Name}";
            return $fullpath;
        }
        \mediadb\Logger::debug("FileRepository.php: No suitable file found");
        return "";
    }
    
    public function getImageFromVideo(int $mid, $device, $imageFile){
        \mediadb\Logger::info("FileRepository.php: Trying to retreive image from video ...");
        // search for the first video and try to extract the image from it
        $query = "SELECT * FROM V_FileWithDevice WHERE REF_Episode = :idms AND REF_Device = :idd AND REF_Filetype = 3 LIMIT 1";
        $parameters = array(
            'idms' => $mid,
            'idd' => $device->ID_Device
        );
        $file = $this->queryFirst($query, $parameters, $this->className);
        if ($file) {
            $fullname = "{$file->SystemPath}files/{$file->Path}{$file->Name}";
            $fileInfo = $this->getID3->analyze($fullname);
            //var_dump($fileInfo);
            
            if ( isset($fileInfo['comments']['picture'][0]['data']) ){
                \mediadb\Logger::info("FileRepository.php: found a picture!");
                
                $image= $fileInfo['comments']['picture'][0]['data'];
                if ( file_put_contents($imageFile) ){
                    \mediadb\Logger::info("FileRepository.php: image successfully written");
                    return true;
                }
            } else {
                \mediadb\Logger::info("FileRepository.php: No Poster found in video description! Using ffmpeg");
                
                $time="00:01:10";
                $cmd = "/usr/bin/ffmpeg -i {$fullname} -ss {$time} -vframes 1 {$imageFile}";
                \mediadb\Logger::debug("FileRepository.php: cmd: {$cmd}");
                $output = shell_exec($cmd);
                \mediadb\Logger::info("FileRepository.php: {$output}");
                //echo "$output";
                return true;
            }
        }
        \mediadb\Logger::warning("FileRepository.php: could also not retreive image from video");
        return false;
    }
    
        
    public function deleteFile($fid){
        try {
            $query = 'DELETE FROM File WHERE ID_File = :fid LIMIT 1';
            $parameters = ['fid'=>$fid];
            return $this->execute($query, $parameters);
        } catch (\Exception $generror) {
            var_dump($generror);
        }
    }
    
}    

//\mediadb\Logger::debug("FileRepository.php: ");