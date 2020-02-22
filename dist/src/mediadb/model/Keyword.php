<?php

/* Keyword.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Keyword model for MediaDB
 */


namespace mediadb\model;

class Keyword extends AbstractModel
{
    public $key;
    public $episodes;
    public $actors;
    
    public function __construct($str){
        //parent::__construct();
        $this->key = $str;
        $this->episodes = array();
        $this->actors = array();
    }
    
    public function addEpisode($id){
        array_push($this->episodes, $id);
    }
    
    public function addActor($id){
        array_push($this->actors, $id);
    }
    
    public function getCount(){
        return count($this->episodes) + count($this->actors);
    }   
}

