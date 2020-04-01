<?php
/* Actor.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Actor model for MediaDB
 */
namespace mediadb\model;

class Actor
{

    public $ID_Actor;
    public $Fullname;
    public $Aliases;
    public $Gender;
    public $Description;
    public $Keywords;
    public $Mugshot;
    public $Thumbnail;
    public $Wallpaper;
    public $Twitter;
    public $Website;
    public $Sites; 
    public $Rating;
    public $Added;
    public $Modified;
    public $Data;
    
    public function __construct(){
    }
    
    /**
     * fixPicture
     * Purpose: Checks, if the picture is set and has an extension;
     * if this is not the case but the respective file exists, picture is set.
     */
    
    public function fixMugshot(){
        if ( !isset($this->Mugshot) || $this->Mugshot == "" ){
            if ( file_exists(ASSETSYSPATH."actors/{$this->Fullname}.jpg") )
                $this->Mugshot = $this->Fullname.".jpg";
            elseif ( file_exists(ASSETSYSPATH."actors/{$this->Fullname}.jpeg") )
                    $this->Mugshot = $this->Fullname.".jpeg";
            elseif ( file_exists(ASSETSYSPATH."actors/{$this->Fullname}.png") )
                    $this->Mugshot = $this->Fullname.".png";
            elseif ( file_exists(ASSETSYSPATH."actors/{$this->Fullname}.webp") )
                    $this->Mugshot = $this->Fullname.".webp";
        }
    }
    
    public function fixWallpaper(){
        if ( !isset($this->Wallpaper) || $this->Wallpaper == "" ){
            if ( file_exists(ASSETSYSPATH."wallpaper/{$this->Fullname}.jpg") )
                $this->Wallpaper = $this->Fullname.".jpg";
            elseif( file_exists(ASSETSYSPATH."wallpaper/{$this->Fullname}.jpeg") )
                $this->Wallpaper = $this->Fullname.".jpeg";
            elseif ( file_exists(ASSETSYSPATH."wallpaper/{$this->Fullname}.png") )
                $this->Wallpaper = $this->Fullname.".png";
            elseif ( file_exists(ASSETSYSPATH."wallpaper/{$this->Fullname}.webp") )
                $this->Wallpaper = $this->Fullname.".webp";
        }
    }
    
    public function fixThumbnail(){
        if ( !isset($this->Thumbnail) || $this->Thumbnail == "" ){
            if ( file_exists(ASSETSYSPATH."actors/thumbnail/{$this->Fullname}.png") )
                $this->Thumbnail = $this->Fullname.".png";
        }
    }
    
    public function printData(){
        $lines = explode("\n", $this->Data);
        print "<table width=100%>";
        foreach($lines as $line){
            $v = explode(':', $line);
            if ( count($v) > 1 )
                print "<tr><td align='right'>$v[0]:&nbsp;</td><td>$v[1]</td></tr>";
            else 
                print nl2br($line);
        }
        print "</table>";
    }
}