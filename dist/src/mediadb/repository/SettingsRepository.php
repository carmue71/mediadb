<?php

/* SettingsRepository.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: DB connection for settings
 * Note: Not much here yet since settings are controlled via conf.php or are hard coded.
 */

namespace mediadb\repository;

use PDO;

class SettingsRepository extends AbstractRepository
{
    private $settings;
    
    // TODO - Insert your code here
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->settings = array();
    }
    
    public function getAll(int $limit = 0, int $offset = 0, String $filter="", String $order="")
    {
        //return null;
        return $this->settings;
    }
    
    public function find($id){
        return $this->settings;
    }
}

