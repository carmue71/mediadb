<?php

/* Episode.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Model for all episodes aka Mediasets
 */

namespace mediadb\model;

class Episode
{   
    public $ID_Episode; 
    public $Title; 
    public $Description; 
    public $Keywords; 
    public $Published; 
    public $REF_Channel; 
    public $PublisherCode; 
    public $Link; 
    public $Picture; 
    public $Wallpaper; 
    public $Comment; 
    public $Rating; 
    public $Viewed; 
    public $Added;
    public $Modified;
    public $Channel;
    
    public function getAge(){
        if ( isset($this->Added)){
            return $this->Added;
        } else return "unknown";
        
    }
    
    public function isWatched(){
        return isset($this->Viewed) && ($this->Viewed > 0);
    }
    
    /**
     * fixPicture
     * Purpose: Checks, if the picture is set and has an extension; 
     * if this is not the case but the respective file exists, picture is changed. 
     */
    
    public function fixPicture(){
        if ( !isset($this->Picture) || $this->Picture == "" ){
            //for (['jpg', 'jpeg'] as $ext)
            if ( file_exists(ASSETSYSPATH."episode/{$this->PublisherCode}.jpg") )
                $this->Picture = $this->PublisherCode.".jpg";
            elseif ( file_exists(ASSETSYSPATH."episode/{$this->PublisherCode}.jpeg") )
                $this->Picture = $this->PublisherCode.".jpeg";
            elseif ( file_exists(ASSETSYSPATH."episode/{$this->PublisherCode}.png") )
                $this->Picture = $this->PublisherCode.".png";
            elseif ( file_exists(ASSETSYSPATH."episode/{$this->PublisherCode}.webp") )
                $this->Picture = $this->PublisherCode.".webp";
        } 
    }
    
    public function fixWallpaper(){
        if ( !isset($this->Wallpaper) || $this->Wallpaper == "" ){
            if ( file_exists(ASSETSYSPATH."wallpaper/{$this->PublisherCode}.jpg") )
                $this->Wallpaper = $this->PublisherCode.".jpg";
            elseif ( file_exists(ASSETSYSPATH."wallpaper/{$this->PublisherCode}.jpeg") )
                    $this->Wallpaper = $this->PublisherCode.".jpeg";
            elseif ( file_exists(ASSETSYSPATH."wallpaper/{$this->PublisherCode}.png") )
                    $this->Wallpaper = $this->PublisherCode.".png";
            elseif ( file_exists(ASSETSYSPATH."wallpaper/{$this->PublisherCode}.webp") )
                    $this->Wallpaper = $this->PublisherCode.".webp";
        }
    }
    
    public function getPicture(int $width=800){
        $size='N';
        switch ( $width ){
            case 800: $size = 'XL'; break;
            case 120: $size = 'S'; break;
            default: $size = 'N';
        }
        
        $poster = isset($this->Picture)?$this->Picture:"";
        return WWW."ajax/getAsset.php?type=poster&size={$size}&file={$poster}";
    }
    
    public function getWallpaper(){
        //TODO: switch to getAsset
        if ( isset($this->Wallpaper) && $this->Wallpaper != ""  ){
            return $this->Wallpaper;
        }
        return "";
    }
    
    public function printRating(){
        print "<span style='font-size: 14px; color: DarkOrange;'>";
        for ($i =0; $i < $this->Rating; $i++){
            print "<i class='fas fa-star color: DarkOrange'></i>";
        }
        print "</span>";
    }
    
    public function printWatched(){
        if ( $this->isWatched() ) {
      		print "&nbsp; <i class='fas fa-check-circle' style='color:Gold;'></i>";
        } else {
      		print "&nbsp; <i class='far fa-circle' style='color:Grey;'></i>";
      	}
    }
}
?>
    
 