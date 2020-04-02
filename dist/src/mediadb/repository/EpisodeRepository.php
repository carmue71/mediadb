<?php

/* EpisodeRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for episode handling
 */

namespace mediadb\repository;

use mediadb\model\Episode;

// use PDO;
//use mediadb\model\Episode;
include_once 'AbstractRepository.php';
//include_once 'Episode.php';

class EpisodeRepository extends AbstractRepository
{

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->filter = "";
        $this->className = "mediadb\\model\\Episode";
        $this->tableName = "V_EpisodeWithChannel";
        $this->orderBy = "Title";
    }

    public function find($id)
    {
        $query = "SELECT * FROM {$this->tableName} WHERE ID_Episode=:id LIMIT 1";
        return $this->queryFirst($query, ['id' => $id], $this->className);
    }

    public function getEpisodesForChannel($id, $msFilter="", $msOrder="", $limit=0, $offset=0 )
    {
        $filter = "REF_Channel={$id}";
        if ( isset($msFilter) && $msFilter <> "" )
            $filter = $filter." AND ".$msFilter;
        if ( !isset($msOrder) || $msOrder == "" )
            $orderBy = "Title";
        else
            $orderBy = $msOrder;
        return $this->getAll($limit, $offset, $filter, $orderBy); //TODO: Limit to normal pagesize
    }
    
    //Needs to be redone, since an episode can appear multiple times in a list.
    public function getEpisodesForWatchList(int $wid, $msFilter="", $msOrder="", $limit=0, $offset=0){
        $filter = "ID_Episode IN (SELECT REF_Episode FROM C_WatchList_Episode WHERE REF_WatchList = {$wid})";
        if ( isset($msFilter) && $msFilter <> "" )
            $filter = $filter." AND ".$msFilter;
            if ( !isset($msOrder) || $msOrder == "" )
                    $orderBy = "Title";
                else
                    $orderBy = $msOrder;
        return $this->getAll($limit, $offset, $filter, $orderBy);
    }
    
    
    
    //$id,    $this->msFilter, $this->msOrder, $this->pageSize, $this->page)
    public function getEpisodesForActor(int $mid, $msFilter="", $msOrder="", $limit=0, $offset=0){
        $filter = "ID_Episode IN (SELECT REF_Episode FROM C_Actor_Episode WHERE REF_Actor = {$mid})";
        if ( isset($msFilter) && $msFilter <> "" )
            $filter = $filter." AND ".$msFilter;
        if ( !isset($msOrder) || $msOrder == "" )
            $orderBy = "Title";
        else
            $orderBy = $msOrder;
        return $this->getAll($limit, $offset, $filter, $orderBy); 
        //$query = "SELECT * FROM {$this->tableName} WHERE ID_Episode IN (SELECT REF_Episode FROM C_Actor_Episode WHERE REF_Actor = :mid)";
        //return $this->queryAll($query, ['mid'=>$mid], $this->className);
    }

   
    public function save($set){
        if ( $set->ID_Episode > -1){
            $query = "UPDATE Episode SET Title = :title, Description = :description, Keywords = :keywords, Published = :published, " . "REF_Channel = :ref_channel, PublisherCode=:publisherCode, Link = :link, Picture = :picture, Wallpaper = :wallpaper, " . "Comment = :comment WHERE ID_Episode = :id";
            $params = array(
                'title' => $set->Title,
                'description' => $set->Description,
                'keywords' => $set->Keywords,
                'published' => $set->Published,
                'ref_channel' => $set->REF_Channel,
                'publisherCode' => $set->PublisherCode,
                'link' => $set->Link,
                'picture' => $set->Picture,
                'wallpaper' => $set->Wallpaper,
                'comment' => $set->Comment,
                'id' => $set->ID_Episode);
        } else { //add a new ms
            $query =  "INSERT INTO Episode (Title,  Description,  Keywords,  Published,  REF_Channel,  PublisherCode,  Link,  Picture,  Wallpaper,  Comment) VALUES"
                ."(:title, :description, :keywords, :published, :ref_channel, :publisherCode, :link, :picture, :wallpaper, :comment)";
               
            $params = array(
                        'title' => $set->Title,
                        'description' => $set->Description,
                        'keywords' => $set->Keywords,
                        'published' => $set->Published,
                        'ref_channel' => $set->REF_Channel,
                        'publisherCode' => $set->PublisherCode,
                        'link' => $set->Link,
                        'picture' => $set->Picture,
                        'wallpaper' => $set->Wallpaper,
                        'comment' => $set->Comment);
        } 
        if ( $this->execute($query, $params) ){
            if ( $set->ID_Episode < 0 )
                $set->ID_Episode = $this->pdo->lastInsertId("Episodes");
                return $set->ID_Episode;
        }
        return -2;
    }
    
    
    
    /**
     * checkDecoration
     * 1 if poster is set, check if the file exists, otherwise set poster to blank
     * 2 if poster is blank but a file with the publishercode exists in the poster directory, set it
     * 3 do the same with the wallpaper
     */
    public function checkDecoration($logLevel){
        $removePictureQry = "UPDATE Episode SET Picture = NULL WHERE ID_Episode = :msid LIMIT 1";
        $removePictureStmt = $this->pdo->prepare($removePictureQry);
        
        $setPictureQry = "UPDATE Episode SET Picture = :pic WHERE ID_Episode = :msid LIMIT 1";
        $setPictureStmt = $this->pdo->prepare($setPictureQry);
        
        $removeWallpaperQry = "UPDATE Episode SET Wallpaper = NULL WHERE ID_Episode = :msid LIMIT 1";
        $removeWallaperStmt = $this->pdo->prepare($removeWallpaperQry);
        
        $query = "Select ID_Episode, Picture, Wallpaper, PublisherCode FROM Episode";
        
        $stmt = $this->pdo->prepare($query);
        if ( $stmt->execute() ){
            while ( $ms = $stmt->fetch() ){
                if ( $ms['Picture'] != "" ){
                    if ( !file_exists(ASSETSYSPATH."episodes/{$ms['Picture']}") ){
                        print "Poster{$ms['Picture']} not found - removing it\n";
                        $removePictureStmt->execute(['msid'=>$ms['ID_Episode']]);
                    }
                } else {
                    if ( file_exists(ASSETSYSPATH."episodes/{$ms['ID_Episode']}.jpg") ){
                        print "Poster {$ms['ID_Episode']}.jpg found - setting it\n";
                        $setPictureStmt->execute(['msid'=>$ms['ID_Episode'], 'pic'=>"{$ms['ID_Episode']}.jpg"]);
                    }
                }
                
                if ( $ms['Wallpaper']!=""){
                    if ( !file_exists(ASSETSYSPATH."wallpaper/{$ms['Wallpaper']}") )
                        print "Wallpaper {$ms['Wallpaper']} not found - removing it\n";
                        $removeWallaperStmt->execute(['msid'=>$ms['ID_Episode']]);
                } else {
                    //TODO: check if the publisher Code exists
                }
            }
        }
    }
    
    public function setFilterFromSelection($text){
        switch ($text){
            case 'Unwatched': $this->filter = 'Viewed = 0';
            case 'Watched':  $this->filter = 'Viewed > 0';
            case 'Top': $this->filter = 'Rating > 4';
            case 'OK': $this->filter = 'Rating > 3';
            case 'Average': $this->filter = 'Rating = 2';
            case 'Flop': $this->filter = 'Rating = 1';
            case 'Unrated': $this->filter = '(Rating is null OR Rating = 0)';
            case 'Recently Added': //TODO: recently ADDED
            case 'All':
            default: $this->filter = '';
        }
    }
    
   public function countEpisodesForChannel(int $ID_Channel, string $tmpfilter){
       if ( $tmpfilter != "" ){
           $this->filter = " REF_Channel = ".$ID_Channel." AND ".$tmpfilter;
       } else { 
           $this->filter = " REF_Channel = ".$ID_Channel;
       }
       return $this->getCount();
   }
   
   public function countEpisodesForWatchList(int $id, string $tmpfilter){
       //TODO: use tmpfilter if necessary
       $query = "SELECT COUNT(*) as Number FROM C_WatchList_Episode WHERE REF_WatchList = :id";
       $paramters = array(
           'id' => $id
       );
       
       $stmt = $this->pdo->prepare($query);
       if ($stmt->execute($paramters)) {
           if ($stmt != null)
               return $stmt->fetch()['Number'];
               else
                   return 0;
       } else {
           print "<pre>";
           var_dump($query);
           var_dump($this->pdo->errorInfo());
           print "</pre>";
           return false;
       }
   }
   
   public function countEpisodesForActor(int $mid, string $msFilter){
       $this->filter = "ID_Episode IN (SELECT REF_Episode FROM C_Actor_Episode WHERE REF_Actor = {$mid})";
       if ( isset($msFilter) && $msFilter <> "" )
           $this->filter = $this->filter." AND ".$msFilter;
       
       return $this->getCount();
   }
   
   /**
    * Count the number of actors that are linked to a given Episode
    * @param int $msid: ID of the Episode
    * @return false: if an error occured, a non negative number representing the linked actors otherwise. 
    */
   public function countActorsForEpisode(int $msid){
        $query = "SELECT COUNT(*) as Number from C_Actor_Episode WHERE REF_Episode = :id_episode";
        $paramters = array(
            'id_episode' => $msid
        );
        
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute($paramters)) {
            if ($stmt != null)
                return $stmt->fetch()['Number'];
            else
                return 0;
        } else {
            print "<pre>";
            var_dump($query);
            var_dump($this->pdo->errorInfo());
            print "</pre>";
            return false;
        }
    }
      
   public function countHDVideos(int $id_episode){
       $query = "SELECT COUNT(*) as Number from File WHERE REF_Episode = :id_episode AND REF_Filetype = 3 AND ResY>719";
       $paramters = array(
           'id_episode' => $id_episode
       );
       $stmt = $this->pdo->prepare($query);
       if ($stmt->execute($paramters)) {
           if ($stmt != null)
               return $stmt->fetch()['Number'];
               else
                   return 0;
       } else {
           print "<pre>";
           var_dump($query);
           var_dump($this->pdo->errorInfo());
           print "</pre>";
           return 0;
       }
   }
   
   public function countHiVideos(int $id_episode){
       $query = "SELECT COUNT(*) as Number from File WHERE REF_Episode = :id_episode AND REF_Filetype = 3 AND ResY>530 AND ResY<720";
       $paramters = array(
           'id_episode' => $id_episode
       );
       $stmt = $this->pdo->prepare($query);
       if ($stmt->execute($paramters)) {
           if ($stmt != null)
               return $stmt->fetch()['Number'];
               else
                   return 0;
       } else {
           print "<pre>";
           var_dump($query);
           var_dump($this->pdo->errorInfo());
           print "</pre>";
           return 0;
       }
   }
   
   public function countFiles(int $id_episode, int $filetype = -1)
   {
       if ($filetype > - 1) {
           $query = "SELECT COUNT(*) as Number from File WHERE REF_Episode = :id_episode AND REF_Filetype = :type";
           $paramters = array(
               'id_episode' => $id_episode,
               'type' => $filetype
           );
       } else {
           $query = "SELECT COUNT(*) as Number from File WHERE REF_Episode = :id_episode";
           $paramters = array(
               'id_episode' => $id_episode
           );
       }
       $stmt = $this->pdo->prepare($query);
       if ($stmt->execute($paramters)) {
           if ($stmt != null)
               return $stmt->fetch()['Number'];
               else
                   return 0;
       } else {
           print "<pre>";
           var_dump($query);
           var_dump($this->pdo->errorInfo());
           print "</pre>";
           return 0;
       }
   }

   
   //----- search --------------------------------------------------------
   public function searchEpisodes(String $searchstring){
       $query = "SELECT * FROM {$this->tableName}"
       ." WHERE Title like '%{$searchstring}%' OR Keywords like '%{$searchstring}%' OR Description like '%{$searchstring}%' OR Comment like '%{$searchstring}%' ".
           " OR PublisherCode like '%{$searchstring}%'";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsWithoutFiles(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE ID_Episode NOT IN ( SELECT REF_Episode FROM File )";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsWithoutMovies(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE ID_Episode NOT IN ( SELECT REF_Episode FROM File WHERE REF_Filetype = 3 )";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsWithoutActors(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE ID_Episode NOT IN ( SELECT REF_Episode FROM Actor )";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsWithMultipleMovies(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE ID_Episode IN ( SELECT REF_Episode from File WHERE REF_Filetype = 3 group by REF_Episode having Count(*) > 2 )";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsWithManyPics(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE ID_Episode IN ( SELECT REF_Episode from File WHERE REF_Filetype = 2 group by REF_Episode having Count(*) > 199 )";
       return $this->queryAll($query, null, $this->className);
   }
   public function findSetsWithoutData(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE Description is Null OR OR Description = ''";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findPartiallyWatchedVideos(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE ID_Episode IN ( SELECT REF_Episode from File WHERE REF_Filetype = 3 AND Progress > 0 AND (Progress/Playtime) < 0.95 )";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findStartedToWatchVideos(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE ID_Episode IN ( SELECT REF_Episode from File WHERE REF_Filetype = 3 AND Progress > 0 AND (Progress/Playtime) < 0.11 )";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findMostlyFinishedVideos(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE ID_Episode IN ( SELECT REF_Episode from File WHERE REF_Filetype = 3 AND (Progress/Playtime) > 0.90 AND Progress < Playtime )";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsModifiedToday(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE DATE(Modified) = CURDATE()";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsModified(int $days){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE DATE(Modified) = DATE_ADD(CURDATE(),INTERVAL {$days} DAY)";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsAddedToday(){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE DATE(Added) = CURDATE()";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function findSetsAdded(int $days){
       $query = "SELECT * FROM V_EpisodeWithChannel WHERE DATE(Added) = DATE_ADD(CURDATE(),INTERVAL {$days} DAY)";
       return $this->queryAll($query, null, $this->className);
   }
   
   public function getWatchLists(){
       $query = "SELECT ID_WatchList, Title FROM WatchList WHERE REF_User={$_SESSION['userid']}";
        $stmt = $this->pdo->prepare($query);
        if (! $stmt) {
            var_dump($query);
            return null;
        }
        $stmt->execute();
        return $stmt->fetchAll();
   }
   
   public function getWatchListsFor(int $msid){
       $query = "SELECT W.ID_WatchList, W.Title, W.Description, C.Position FROM C_WatchList_Episode C ".
            " INNER JOIN WatchList W ON C.REF_WatchList = W.ID_WatchList ".
            " WHERE W.REF_User=:user AND C.REF_Episode = :msid ORDER BY C.Position";
       $params = ['msid'=>$msid, 'user'=>$_SESSION['userid']];
       $stmt = $this->pdo->prepare($query);
       if (! $stmt) {
           var_dump($query);
           return null;
       }
       if ( $stmt->execute($params) )
            return $stmt->fetchAll();
       var_dump($query);
       var_dump($params);
       return null;
   }
   
   public function addToHistory(int $msid) {
       try {
            // Check if the current entry is not already the last entry
            $lastID = $this->getLastHistoryEntry();
            if ($lastID != $msid) {
                $wid = $_SESSION['history'];
                if ( $wid == null ) 
                    $wid = 0;
                $position = $this->findNextPosition($wid);
                $query = 'INSERT INTO C_WatchList_Episode (REF_Episode, REF_WatchList, Position) VALUES (:msid, :wid, :pos)';
                $params = [
                    'msid' => $msid,
                    'wid' => $wid,
                    'pos' => $position
                ];

                $stmt = $this->pdo->prepare($query);
                if (! $stmt) {
                    var_dump($query);
                    return;
                }
                if (! $stmt->execute($params)) {
                    var_dump($query);
                    var_dump($params);
                }
            }
        } catch (\Exception $e) {
            echo "Genral Problem: " . $e->getMessage() . "\n";
            return false;
        }
   }
   
   private function getLastHistoryEntry(){
       try {
            $query = "SELECT C.REF_Episode AS LastSet FROM C_WatchList_Episode C WHERE C.REF_WATCHLIST = :wlid1 AND C.Position = (SELECT MAX(X.Position) FROM C_WatchList_Episode X WHERE X.REF_WATCHLIST = :wlid2)";
            $params = [
                'wlid1' => $_SESSION['history'],
                'wlid2' => $_SESSION['history']
            ];
            $stmt = $this->pdo->prepare($query);
            if (! $stmt) {
                var_dump($query);
                return 0;
            }
            if ($stmt->execute($params))
                return $stmt->fetch()['LastSet'];
            var_dump($query);
            var_dump($params);
            return 0;
        } catch (\Exception $e) {
            echo "Genral Problem: " . $e->getMessage() . "\n";
            return 0;
        }
   }
   
   function findNextPosition(int $id_watchlist){
       try {
            $query = "SELECT MAX(Position) as Pos FROM C_WatchList_Episode WHERE REF_WatchList=:id";
            $stmt = $this->pdo->prepare($query);
            if ($stmt && $stmt->execute([
                'id' => $id_watchlist
            ])) {
                $id = $stmt->fetch();
                if ($id == null) {
                    var_dump($query);
                    return -1;
                }
                return $id['Pos'] + 1;
            }
            return 0;
        } catch (\Exception $e) {
            echo "Genral Problem: " . $e->getMessage() . "\n";
            return -1;
        }
   }
   
   public function getChannelWallpaper(int $sid){
       try {
            $query = "SELECT Wallpaper FROM Channel WHERE ID_Channel=:id";
            $stmt = $this->pdo->prepare($query);
            if ($stmt && $stmt->execute([
                'id' => $sid
            ])) {
                $res = $stmt->fetch();
                if ($res == null) {
                    var_dump($query);
                    return - 1;
                }
                return $res['Wallpaper'];
            }
        } catch (\Exception $e) {
            echo "Genral Problem: " . $e->getMessage() . "\n";
        }
        return "";
   }//getChannelWallpaper
   
}