<?php

//check session
session_start();
if ( !isset($_SESSION['login']) || empty($_SESSION['login']) ){
    header('location: /mediadb/login.php');
    exit();
}

//load config
define('SRC_PATH', '/opt/MediaDB/src/');
include_once SRC_PATH.'mediadb/conf.php';
//include_once SRC_PATH.'tools/databasetools.php';
   
//check parameters
if ( isset($_GET['path'])){
    $path = $_GET['path']; 
} else {
    createErrorThumb("No Image Provided");
    exit;
}

if ( isset($_GET['type'])){
    $type = $_GET['type'];
} else {
    $type = "thumb";
}


if ( !file_exists($path) ){
    createErrorThumb("file_not_found");
    exit(0);
}

switch ( $type ){
    case 'video': returnVideo($path); break;
    case "lgthumb": createThumb($path,256); break;
    case "thumb": createThumb($path,128); break;
    case "img": returnImage($path); break;
    default: 
        createErrorThumb("Unknown Type");
} 

exit(0);

function createThumb($file, $size)
{
    // todo: define this in the conf.
    $maxWidth = $size;
    $maxHeight = $size;
    $image = null;
    
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext == 'jpg' || $ext == 'jpeg') {
        $image = imagecreatefromjpeg($file);
    } elseif ($ext == 'gif' ) {
        $image = imagecreatefromgif($file);
    } elseif ($ext == 'webp' ) {
        $image = imagecreatefromwebp($file);
    } else {
        $image = imagecreatefrompng($file);
    }
    
    if ($image) {
        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = floor($scale * $width);
        $newHeight = floor($scale * $height);
        $tmpImg = imagecreatetruecolor($maxWidth, $maxHeight);
        // imagesavealpha($tmpImg, true);
        $white = imagecolorallocate($tmpImg, 0, 0, 0);
        imagefill($tmpImg, 0, 0, $white);
        // imagefilledellipse($png, 400, 300, 400, 300, $red); //for the thumbnails
        imagecopyresized($tmpImg, $image, ($maxWidth - $newWidth) / 2, ($maxHeight - $newHeight) / 2, 0, 0, $newWidth, $newHeight, $width, $height);
        // imagecopyresampled($tmpImg, $image, ($maxWidth - $newWidth) / 2, ($maxHeight - $newHeight) / 2, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);
        $image = $tmpImg;
    }
    
    if ($image == null) {
        createErrorThumb("Error loading image");
        exit(0);
    }
    
    header("Content-type: image/jpg");
    imagejpeg($image);
    imagedestroy($image);
}

function returnImage($file)
{
    $image = null;
    
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext == 'jpg' || $ext == 'jpeg') {
        $image = imagecreatefromjpeg($file);
        if ($image == null) {
            createErrorThumb("Error loading image");
            exit(0);
        }
        header("Content-type: image/jpg");
        imagejpeg($image);
        imagedestroy($image);
    } elseif ($ext == 'gif' ) {
        $image = imagecreatefromgif($file);
        if ($image == null) {
            createErrorThumb("Error loading image");
            exit(0);
        }
        header("Content-type: image/gif");
        imagegif($image);
        imagedestroy($image);
    } elseif ($ext == 'webp' ) {
        $image = imagecreatefromwebp($file);
        if ($image == null) {
            createErrorThumb("Error loading image");
            exit(0);
        }
        header("Content-type: image/webp");
        imagewebp($image);
        imagedestroy($image);
    }else {
        $image = imagecreatefrompng($file);
        if ($image == null) {
            createErrorThumb("Error loading image");
            exit(0);
        }
        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);
    }    
}

function createErrorThumb(string $error=""){
    //todo: define this in the conf.
    $maxWidth = 128;
    $maxHeight = 128;
    
    switch ($error){
        case "file_not_found":
            $image = imagecreatefrompng(ASSETSYSPATH."system/filenotfound_thumb.png");
            //todo load predfined errror images
            break;
        case "access_denied":
            $image = imagecreatefrompng(ASSETSYSPATH."system/access_denied_thumb.png");
            break;
        default:
            $image = imagecreatetruecolor($maxWidth, $maxHeight);
            $grey = imagecolorallocate($image, 128, 128, 128);
            imagefill($image, 0, 0, $grey);
    }
    
    
    
    //print $error;
    
    header("Content-type: image/jpg");
    imagejpeg($image);
    imagedestroy($image);
}

function returnVideo($path){
    $size=filesize($path);
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $vidtype = "";
    switch ($ext){
        case "wmv":
            $vidtype='video/x-ms-wmv';
            break;
        case "mkv":
            $vidtype='video/x-matroska';
            break;
        case "avi":
            $vidtype='video/avi';
            break;
        case "mp4":
        default:
            $vidtype='video/mp4';
    }
    
    
    $fm=@fopen($path,'rb');
    if(!$fm) {
        // You can also redirect here
        header ("HTTP/1.0 404 Not Found");
        die();
    }
    
    $begin=0;
    $end=$size;
    $matches = array();
    
    if(isset($_SERVER['HTTP_RANGE'])) {
        if(preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
            $begin=intval($matches[0]);
            if(!empty($matches[1])) {
                $end=intval($matches[1]);
            }
        }
    }
    
    if($begin>0||$end<$size)
        header('HTTP/1.0 206 Partial Content');
        else
            header('HTTP/1.0 200 OK');
            
            header("Content-Type: ".$vidtype);
            header('Accept-Ranges: bytes');
            header('Content-Length:'.($end-$begin));
            header("Content-Disposition: inline;");
            header("Content-Range: bytes $begin-$end/$size");
            header("Content-Transfer-Encoding: binary\n");
            header('Connection: close');
            
            $cur=$begin;
            fseek($fm,$begin,0);
            
            while(!feof($fm)&&$cur<$end&&(connection_status()==0))
            { print fread($fm,min(1024*16,$end-$cur));
            $cur+=1024*16;
            usleep(1000);
            }
            die();
    
    
}
