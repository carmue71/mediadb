<?php

/* Device.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Model for devices
 */

namespace mediadb\model;

class Device
{
    public $ID_Device;
    public $Name;
    public $Path;
    public $DisplayPath;
    public $Removable;
    public $Network;
    public $Comment;

    public function isActive(){
        if ( isset ($this->Path) )
            return file_exists($this->Path) && file_exists($this->Path . "/files/");
        else{
            return false;
        }
    }
}

