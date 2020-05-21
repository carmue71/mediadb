<?php

/* DeviceRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for channel handling
 */

namespace mediadb\repository;

use PDO;
use PDOException;


class ChannelRepository extends AbstractRepository
{

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
            $this->filter = "";
            $this->className = "\\mediadb\\model\\Channel";
            $this->tableName = "Channel"; 
            $this->orderBy = "Name";
            $this->idColumn = "ID_Channel";
    }
    
    public function insert(){
        try {
            $query = "INSERT INTO Channel (Name,  Site,  Logo,  Comment, Wallpaper, DefaultSetPath, StudioType) VALUES (:name, :site, :logo, :comment, :wallpaper, :dsp, :type)";
            $parameters = array(
                'name' => $_POST['name'],
                'site' => $_POST['site'],
                'logo' => $_POST['logo'],
                'comment' => $_POST['comment'],
                'wallpaper' => $_POST['wallpaper'],
                'dsp' => $_POST['setpath'],
                'type' => $_POST['type']
            );
            $stmt = $this->pdo->prepare($query);
            if (!$stmt){
                print "<pre>";
                print "Cannot Prepare Statemtent!\n";
                var_dump($parameters);
                var_dump($query);
                var_dump($this->pdo->errorInfo());
                print "</pre>";
                return null;
            }
            if ( ! $stmt->execute($parameters)) {
                
                print "<pre>";
                var_dump($parameters);
                var_dump($query);
                var_dump($this->pdo->errorInfo());
                print "</pre>";
                return null;
            }
            return $this->find($this->pdo->lastInsertId("Channel"));
        } catch (PDOException $e) {
            echo "Failed: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    function update($id){
        try {
            $query = "UPDATE Channel SET Name = :name, Site = :site, Logo = :logo, Comment = :comment, "
                ."Wallpaper = :wallpaper, DefaultSetPath = :dsp, StudioType = :type WHERE ID_Channel = :id";
                    $parameters = array(
                        'name'=>$_POST['name'],
                        'site'=>$_POST['site'],
                        'logo'=>$_POST['logo'],
                        'comment'=>$_POST['comment'],
                        'wallpaper'=>$_POST['wallpaper'],
                        'dsp' => $_POST['setpath'],
                        'type' => $_POST['type'],
                        'id'=>$id );
                    $stmt = $this->pdo->prepare($query);
                    if ( !$stmt ){
                        print "<pre>";
                        print "<h1>Error Preparing Channel</h1>";
                        print "<p>Parameters:";var_dump($parameters);
                        print "</p><p>Query:";var_dump($query);
                        print "</p><p>Error:";var_dump($this->pdo->errorInfo());
                        print "</pre>";
                        return false;
                    }
                    if ( !$stmt->execute($parameters) ){
                        print "<pre>";
                        print "<h1>Error Updating Channel</h1>";
                        print "<p>Parameters:";var_dump($parameters);
                        print "</p><p>Query:";var_dump($query);
                        print "</p><p>Error:";var_dump($this->pdo->errorInfo());
                        print "</pre>";
                        return false;
                    }
                    return true;
        } catch (PDOException $e){
            echo "Failed: ".$e->getMessage()."\n";
            return false;
        }
    }
    
    public function scan($channel){
        $container = new \mediadb\Container();
        $deviceRepository = $container->make('DeviceRepository');
        
        \mediadb\Logger::debug("ChannelRepoistory: Scanning all availlable devices for channel {$channel->Name}");
        
        $devices = $deviceRepository->getAll();
        
        foreach ($devices as $device){
            if (isset($device)) {
                #\mediadb\Logger::debug("scanner.php: scan started on {$device->Name}!------------------");
                $deviceRepository->scanDevice($device, false,false, false, -1, $channel->ID_Channel);
                #\mediadb\Logger::debug("scanner.php: scan finished on {$device->Name}!---------------");
            } else {
                \mediadb\Logger::error("ChannelRepository.php: device not defined!");
            }
        }
        $deviceRepository->showStatistics(false);
        
        \mediadb\Logger::info("ChannelRepository.php: Scan finished!");
    }
}