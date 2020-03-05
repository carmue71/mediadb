<?php

/* KeywordRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for device handling
 */

namespace mediadb\repository;

use PDO;
use mediadb\model\Keyword;

class KeywordRepository extends AbstractRepository
{
    private $keywords;
   
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->keywords = array();
    }
    
    public function getAll(int $limit = 0, int $offset = 0, String $filter="", String $order="")
    {
        $this->findKeywords(true);
        $this->findKeywords(false);
        return $this->keywords;
    }
    
    public function find($id){
        $keyword = new Keyword($id);
        $keyword->episodes = $this->getEpisodesByKeyword($id);
        $keyword->actors = $this->getActorssByKeyword($id);
        return $keyword;
    }
    
    private function findKeywords(bool $doEpisode)
    {
        if ($doEpisode)
            $query = "Select ID_Episode, Keywords FROM Episode";
            else
                $query = "Select ID_Actor, Keywords FROM Actor";
                $data = $this->pdo->query($query);
                if ($data != null) {
                    foreach ($data as $entry) {
                        $keys = explode(',', $entry['Keywords']);
                        foreach ($keys as $strkey) {
                            $strkey = strtolower(trim($strkey));
                            if ($strkey == "")
                                continue;
                                $found = false;
                                $i = 0;
                                foreach ($this->keywords as $kw) {
                                    $res = strcmp($kw->key, $strkey);
                                    if ($res == 0) { // found
                                        $found = true;
                                        if ($doEpisode)
                                            $kw->addEpisode($entry['ID_Episode']);
                                        else
                                            $kw->addActor($entry['ID_Actor']);
                                        break;
                                    } else if ($res > 0) {
                                        $found = true;
                                        $newKw = new Keyword($strkey);
                                        if ($doEpisode)
                                            $newKw->addEpisode($entry['ID_Episode']);
                                        else
                                            $newKw->addActor($entry['ID_Actor']);
                                        array_splice($this->keywords, $i, 0, array($newKw));
                                        break;
                                    }
                                    $i ++;
                                }
                                if (! $found) {
                                    $kw = new Keyword($strkey);
                                    if ($doEpisode)
                                        $kw->addEpisode($entry['ID_Episode']);
                                    else
                                        $kw->addActor($entry['ID_Actor']);
                                    array_push($this->keywords, $kw);
                                }
                        }
                    }
                }
    }
    
    private function getEpisodesByKeyword(String $key){
        $query = "SELECT ID_Episode, Title, REF_Channel, Picture FROM Episode WHERE Keywords LIKE '%".$key."%'";
        $stmt = $stmt = $this->pdo->prepare($query);
        if ( $stmt->execute() )
            return $stmt->fetchAll();
        return null;
    }
    
    private function getActorssByKeyword(String $key){
        $query = "SELECT ID_Actor, Fullname, Mugshot FROM Actor WHERE Keywords LIKE '%".$key."%'";
        $stmt = $stmt = $this->pdo->prepare($query);
        if ( $stmt->execute() )
            return $stmt->fetchAll();
        return null;
    }
}
