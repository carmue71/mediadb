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
include_once SRC_PATH.'tools/databasetools.php';
   
//check parameters
if ( isset($_GET['fid'])){
    $fid = $_GET['fid']; 
} else {
   die ('no image provided!');
}

//connect to database
$pdo = connectToDatabase();
if ( !$pdo ){
    createErrorThumb('cannot connect to database - pls check!');
    exit;
}
//if the device is online    
//retrieve the path from the device

$file = getFile($pdo, $fid);
if ( !isOnline($file['DevPath']) ){
    createErrorThumb("Device is not online / path not found");
    exit;
}

//check if we have an image
if ( $file['Type'] <> 2){
    createErrorThumb("File is not an image");
    exit;
}


//todo: define this in the conf.
$maxWidth = 128;
$maxHeight = 128;
$image = null;

$path = $file['DevPath']."files/".$file['Path'].$file['Name'];
echo $path;
exit;

if ( !file_exists($file) ){
    createErrorThumb("File does not exist: ".$file);
    exit;
}
    
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext == 'jpg' || $ext == 'jpeg') {
        $image = imagecreatefromjpeg($file);
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
    //imagesavealpha($tmpImg, true);
    $white = imagecolorallocate($tmpImg, 0, 0, 0);
    imagefill($tmpImg, 0, 0, $white);
    // imagefilledellipse($png, 400, 300, 400, 300, $red); //for the thumbnails
    imagecopyresized($tmpImg, $image, ($maxWidth - $newWidth) / 2, ($maxHeight - $newHeight) / 2, 0, 0, $newWidth, $newHeight, $width, $height);
    //imagecopyresampled($tmpImg, $image, ($maxWidth - $newWidth) / 2, ($maxHeight - $newHeight) / 2, 0, 0, $newWidth, $newHeight, $width, $height);
    imagedestroy($image);
    $image = $tmpImg;
} 

if ( $image == null ){  
    $image = imagecreatetruecolor($maxWidth, $maxHeight);
    $grey = imagecolorallocate($image, 128, 128, 128);
    imagefill($image, 0, 0, $grey);
}
    
header("Content-type: image/jpg");
imagejpeg($image);
imagedestroy($image);
exit(0);

function getfile($pdo, int $fid){
    $query = "SELECT F.Name, F.Path, F.REF_Filetype as Type, D.Name as Device, D.Path as DevPath ".
        "FROM File F INNER JOIN Device D ON F.REF_Device = D.ID_Device WHERE F.ID_File=:fid LIMIT 1";
    $stmt = $pdo->prepare($query);
    if ($stmt && $stmt->execute(['fid' => $fid])) 
        return $stmt->fetch();
    return false;
}
    
function isOnline($path){
    return file_exists($path) && file_exists($path . "/files/");
}

function createErrorThumb(string $error=""){
    //todo: define this in the conf.
    $maxWidth = 128;
    $maxHeight = 128;
    
    $image = imagecreatetruecolor($maxWidth, $maxHeight);
    $grey = imagecolorallocate($image, 128, 128, 128);
    imagefill($image, 0, 0, $grey);
    
    print $error;
    //todo load predfined errror images
    //header("Content-type: image/jpg");
    //imagejpeg($image);
    //imagedestroy($image);
}