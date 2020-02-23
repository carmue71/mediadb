<?php

/* WatchListRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for handling watchlists
 */

namespace mediadb\repository;

use PDO;
use PDOException;


class WatchListRepository extends AbstractRepository
{

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
            $this->filter = "";
            $this->className = "\\mediadb\\model\\WatchList";
            $this->tableName = "WatchList"; 
            $this->orderBy = "Title";
            $this->idColumn = "ID_WatchList";
    }
    
    public function insert(){
        try {
            $query = "INSERT INTO WatchList (Title,  REF_User,  Description) VALUES (:title, :user, :description)";
            $parameters = array(
                'title' => $_POST['title'],
                'user' => $_SESSION['userid'],
                'description' => $_POST['description']
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
         return $this->find($this->pdo->lastInsertId("WatchList"));
        } catch (PDOException $e){
            echo "Failed: ".$e->getMessage()."\n";
            return false;
        }
    }
    
    function update($id){
        try {
            $query = "UPDATE WatchList SET Title = :title, Description = :description WHERE ID_WatchList = :id";
            $parameters = array(
                        'title'=>$_POST['title'],
                        'description'=>$_POST['description'],
                        'id'=>$id );
            $stmt = $this->pdo->prepare($query);
                    if ( !$stmt->execute($parameters) ){
                        print "<pre>";
                        print "<h1>Error Updating WatchList</h1>";
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

    public function listEpisodes(int $id_watchlist, int $limit = 0, int $offset = 0){
        $query = "SELECT W.Position, M.ID_Episode, M.Title, M.Description, M.Picture, M.REF_Channel, S.Name, S.Logo "
            ." FROM `C_WatchList_Episode` W INNER JOIN Episode M ON W.REF_Episode = M.ID_Episode "
            ." INNER JOIN Channel S ON S.ID_Channel = M.REF_Channel "
            ." WHERE REF_WatchList = :id ORDER BY Position LIMIT {$offset}, {$limit}";
        $params = ['id'=> $id_watchlist];
        $stmt = $this->pdo->prepare($query);
        if (! $stmt) {
            var_dump($query);
            return null;
        }
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}