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
            $query = "INSERT INTO Channel (Name,  Site,  Logo,  Comment, Wallpaper, DefaultSetPath, ChannelType) VALUES (:name, :site, :logo, :comment, :wallpaper, :dsp, :type)";
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
            if (! $stmt->execute($parameters)) {
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
                ."Wallpaper = :wallpaper, DefaultSetPath = :dsp, ChannelType = :type WHERE ID_Channel = :id";
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
}