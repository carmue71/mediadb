<?php

/* ActorRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for handling actors and actresse 
 */

namespace mediadb\repository;

use PDO;
use PDOException;
use mediadb\model\Actor;


class ActorRepository extends AbstractRepository
{

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
            $this->filter = "";
            $this->className = "\\mediadb\\model\\Actor";
            $this->tableName = "Actor"; 
            $this->orderBy = "Fullname";
            $this->idColumn="ID_Actor";
    }
    
    public function findActorsForEpisode(int $ID_Episode){
        $query = "SELECT * FROM Actor WHERE ID_Actor IN (SELECT REF_Actor FROM C_Actor_Episode WHERE REF_Episode = :mid)";
        return $this->queryAll($query, ['mid' => $ID_Episode]);
    }
    
    public function findActorByName(string $name){
        $query = "SELECT ID_Actor from Actor WHERE Fullname = :fullname";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['fullname'=>$name]);
        $found = $stmt->fetch();
        if ( $found )
            return $found['ID_Actor'];
        else 
            return null;
    }
    
    public function isAlreadyLinked(int $id_actor, int $id_episode){
        $query = "SELECT count(*) as C FROM C_Actor_Episode WHERE REF_Actor = :ref_actor AND REF_Episode = :ref_episode";
        $parameters = array('ref_actor'=>$id_actor, 'ref_episode'=>$id_episode);
        $stmt = $this->pdo->prepare($query);
        if ( $stmt->execute($parameters) ){
            $c = $stmt->fetch()['C'];
            return $c > 0;
        }
        else {
            print "<p>Something wrong with query {$query} with {$parameters}</p>";
            return false;
        }
    }
    
    public function linkActorToEpisode(int $id_actor, int $id_episode, String $comment=""){
        $query = "INSERT INTO C_Actor_Episode (REF_Actor, REF_Episode, Comment) VALUES (:ref_actor, :ref_episode, :comment)";
        $parameters = array('ref_actor'=>$id_actor, 'ref_episode'=>$id_episode, 'comment'=>$comment);
        $stmt = $this->pdo->prepare($query);
        if ( !$stmt->execute($parameters)){
            print "<pre> <br><br>";
            var_dump($query);
            print "<br>";
            var_dump($parameters);
            var_dump($this->pdo->errorInfo());
            print "</pre>";
            return false;
        }
        return true;
    }
    
    public function save(Actor $actor){
        if ( $actor->ID_Actor < 0){
            $query = "INSERT INTO Actor "
                ." (Fullname, Aliases, Gender, Description, Mugshot, Wallpaper, Keywords, Twitter, Website, Thumbnail, Sites, Data) "
                    ." VALUES "
                        ."(:fullname, :aliases, :gender, :description, :mugshot, :wallpaper, :keywords, :twitter, :website, :thumbnail, :sites, :data)";
            $parameters = array(
                            'fullname'=>$actor->Fullname,
                            'aliases'=>$actor->Aliases,
                            'gender'=>$actor->Gender,
                            'description'=>$actor->Description,
                            'mugshot'=>$actor->Mugshot,
                            'wallpaper'=>$actor->Wallpaper,
                            'keywords'=>$actor->Keywords,
                            'twitter'=>$actor->Twitter,
                            'website' =>$actor->Website,
                            'thumbnail'=>$actor->Thumbnail,
                            'sites'=>$actor->Sites,
                            'data'=>$actor->Data
            );
        } else {
            $query = "UPDATE Actor SET Fullname = :fullname, Aliases = :aliases, Gender = :gender, "
                ." Description =:description, Mugshot = :mugshot, Wallpaper = :wallpaper, Keywords = :keywords,"
                    ." Twitter = :twitter, Website = :website, Thumbnail=:thumbnail, Sites = :sites, Data = :data WHERE ID_Actor = :id";
                    $parameters = array(
                        'fullname'=>$actor->Fullname,
                        'aliases'=>$actor->Aliases,
                        'gender'=>$actor->Gender,
                        'description'=>$actor->Description,
                        'mugshot'=>$actor->Mugshot,
                        'wallpaper'=>$actor->Wallpaper,
                        'keywords'=>$actor->Keywords,
                        'twitter'=>$actor->Twitter,
                        'website' =>$actor->Website,
                        'thumbnail'=>$actor->Thumbnail,
                        'sites'=>$actor->Sites,
                        'data'=>$actor->Data,
                        'id' => $actor->ID_Actor);
        }
        try {
            $stmt = $this->pdo->prepare($query);
            if ( !$stmt || !$stmt->execute($parameters) ){
                print "<pre>";
                var_dump($parameters);
                var_dump($query);
                var_dump($this->pdo->errorInfo());
                print "</pre>";
                return null;
            }
            if ( $actor->ID_Actor == -1 )
                $actor->ID_Actor = $this->pdo->lastInsertId("Actor");
            return true;
        } catch (PDOException $e){
            echo "Failed: ".$e->getMessage()."\n";
            return false;
        }
    }
    
    function update($id){
        try {
            $query = "UPDATE Actor SET Fullname = :fullname, Aliases = :aliases, Gender = :gender, "
                ." Description =:description, Mugshot = :mugshot, Wallpaper = :wallpaper, Keywords = :keywords,"
                    ." Twitter = :twitter, Website = :website, Thumbnail=:thumbnail, Sites = :sites, Data = :data WHERE ID_Actor = :id";
                    $parameters = array(
                        'fullname'=>$_POST['fullname'],
                        'aliases'=>$_POST['aliases'],
                        'gender'=>$_POST['gender'],
                        'description'=>$_POST['description'],
                        'mugshot'=>$_POST['mugshot'],
                        'wallpaper'=>$_POST['wallpaper'],
                        'keywords'=>$_POST['keywords'],
                        'twitter'=>$_POST['twitter'],
                        'website' =>$_POST['website'],
                        'thumbnail'=>$_POST['thumbnail'],
                        'sites'=>$_POST['sites'],
                        'data'=>$_POST['moddata'],
                        'id'=>$id
                    );
                    $stmt = $this->pdo->prepare($query);
                    if ( !$stmt->execute($parameters) ){
                        print "<pre>";
                        var_dump($parameters);
                        var_dump($query);
                        var_dump($this->pdo->errorInfo());
                        print "</pre>";
                        return false;
                    }
                    return true;
        } catch (PDOException $e){
            echo "Failed: ".$e->getMessage()."\n";
            return false;
        }
    }
    
    //----- search --------------------------------------------------------
    public function searchActors(String $searchstring){
        $query = "SELECT * FROM {$this->tableName}"
        ." WHERE Fullname like '%{$searchstring}%' OR Keywords like '%{$searchstring}%' OR Description like '%{$searchstring}%' OR Aliases like '%{$searchstring}%'";
                
        return $this->queryAll($query, null, $this->className);
    }
    
    public function findActorsWithoutSet(){
        $query = "SELECT * FROM Actor WHERE ID_Actor NOT IN (SELECT REF_Actor FROM C_Actor_Episode)";
        return $this->queryAll($query, null, $this->className);
    }
    
    public function findActorsWithoutSites(){
        $query = "SELECT * FROM Actor WHERE (Website is Null OR Website = '') AND (Sites is Null OR Sites = '')";
        return $this->queryAll($query, null, $this->className);
    }
    
    public function findActorsWithoutMugshots(){
        $query = "SELECT * FROM Actor WHERE Mugshot is Null OR Mugshot = ''";
        return $this->queryAll($query, null, $this->className);
    }

    public function findActorsWithoutData(){
        $query = "SELECT * FROM Actor WHERE Description is Null OR Description = ''";
        return $this->queryAll($query, null, $this->className);
    }
}
        

