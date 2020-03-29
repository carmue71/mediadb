<?php

/* Channel.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Model for all channels, i.e. studios, channels or series
 */
namespace mediadb\model;


class Channel extends  AbstractModel
{
    public $ID_Channel;   
    public $Name;
    public $Site;
    public $DefaultSetPath;
    public $Logo;
    public $Comment;
    public $Wallpaper;
    public $StudioType;
    public $Modified;
    public $Added;
        
    public function __construct(){
    }
    
    public function  getLogo(){
        if ( isset($this->Logo) && $this->Logo != "" )
            return $this->Logo;
        else
            return "";
    }
    
    public function  getWallpaper(){
        if ( isset($this->Wallpaper) && $this->Wallpaper != "" )
            return $this->Wallpaper;
        else
            return "";
    }
}