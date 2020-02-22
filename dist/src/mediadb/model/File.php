<?php

/* File.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Model for files
 */

namespace mediadb\model;

class File extends AbstractModel
{
    public $ID_File;
    public $Name;
    public $Path;
    public $Availlable;
    public $REF_Device;
    public $REF_Episode;
    public $REF_Filetype;
    public $Title;
    public $Keywords;
    public $Comment;
    public $Rating;
    public $FileInfo;
    public $Size;
    public $ResX;
    public $ResY;
    public $Created;
    public $Modified;
    public $Playtime;
    public $Progress;
    
    public $Device;
    public $DevicePath;
    public $SystemPath;
    
    public function  printSize(){
        if ( empty($this->Size) )return "--";
        if ( $this->Size < 1024 ) return $this->Size."B";
        if ( $this->Size < 1024*1024 ) return round($this->Size/1024)."k";
        if ( $this->Size < 1024*1024*1024 ) return round($this->Size/(1024*1024))."M";
        return round($this->Size/(1024*1024*1024))."G";
    }
    
    public function printResolution(){
        if ( empty($this->ResY) )return "--";
        return $this->ResX." x ".$this->ResY;
    }
    
    public function printHD(){
        if ( empty($this->ResY) )return "--";
        return $this->ResY>719?"HD":"SD";
    }
    
    public function getFullpath(){
        return $this->DevicePath."files/".$this->Path.$this->Name;
    }
    
    public function getProgress(){
        if ( !isset($this->Progress) || $this->Progress == null ) return 0;
        return $this->Progress;
    }
    
    public function getInternalPath(){
        return $this->SystemPath."files/".$this->Path.$this->Name;
    }
    
    public function printType(){
        $ext=pathinfo($this->Name, PATHINFO_EXTENSION);
        switch(strtolower($ext)){
            case "avi": print "video/avi"; break;
            case "mkv": print "video/x-matroska"; break;
            case "webm": print "video/webm"; break;
            case "ppt": case "ppz": case "pps":
            case "pptx": print "application/mspowerpoint"; break;
            case "gif": print "image/gif"; break;
            case "png":
                print "image/png"; break;
            case "jpe": 	
            case "jpeg": 	
            case "jpg":
                print "image/jpeg"; break;
            case "zip":
                print "application/zip"; break;
            case "mp4":
            case "m4v":
                print "video/mp4"; break;
                
            /*
             * sgml 	text/sgml
             * tar 	application/x-tar
             * tcl 	application/x-tcl
             * 
             
.class 	application/octet-stream 	  	 	application/mspowerpoint
.css 	text/css 	  	.ps 	application/postscript
.doc 	application/msword 	  	.qt 	video/quicktime
.eps 	application/postscript 	  	.ra 	audio/x-realaudio
.exe 	application/octet-stream 	  	.ram 	audio/x-pn-realaudio
 	  	.rm 	audio/x-pn-realaudio
.gtar 	application/x-gtar 	  	.rpm 	audio/x-pn-realaudio-plugin
.gz 	application/x-gzip 	  	.rtf 	text/rtf
.htm 	text/html 	  	.rtx 	text/richtext
.html 	text/html 	  	.sgm 	text/sgml
.js 	application/x-javascript 	  	.tif 	image/tiff
.midi 	audio/midi 	  	.tiff 	image/tiff
.mov 	video/quicktime 	  	.txt 	text/plain
.movie 	video/x-sgi-movie 	  	.vrml 	model/vrml
.mp2 	audio/mpeg 	  	.wav 	audio/x-wav
.mp3 	audio/mpeg 	  	.wrl 	model/vrml
.mpe 	video/mpeg 	  	.xbm 	image/x-xbitmap
.mpeg 	video/mpeg 	  	.xlc 	application/vnd.ms-excel
.mpg 	video/mpeg 	  	.xll 	application/vnd.ms-excel
.mpga 	audio/mpeg 	  	.xlm 	application/vnd.ms-excel
.pbm 	image/x-portable-bitmap 	  	.xls 	application/vnd.ms-excel
.pdf 	application/pdf 	  	.xlw 	application/vnd.ms-excel
 	  	.xml 	text/xml
*/
        }
    }
    
    public function printPlaytime(){
        if ( empty($this->Playtime )) return "";
        $m = floor($this->Playtime/60);
        $sec = $this->Playtime-($m*60);
        $hrs = floor($m/60);
        $min = $m-$hrs*60; 
        return str_pad($hrs,2,'0', STR_PAD_LEFT).':'.str_pad($min,2,'0', STR_PAD_LEFT).':'.str_pad($sec,2,'0', STR_PAD_LEFT);
    }
}

