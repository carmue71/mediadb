<?php

/* WatchList.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Model for watchlists
 */

namespace mediadb\model;

class WatchList extends  AbstractModel
{
    public $ID_WatchList;   
    public $REF_User;
    public $Title;
    public $Description;
        
    public function __construct(){

    }
}

