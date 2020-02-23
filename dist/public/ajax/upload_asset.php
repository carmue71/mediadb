<?php

session_start();

define('SRC_PATH', '/opt/MediaDB/src/');

require_once SRC_PATH.'mediadb/conf.php';
require_once SRC_PATH.'tools/texttools.php';

if ( !isset($_SESSION['login']) || $_SESSION['login'] == null) {
    die ("Error: You seem not to be logged in!");
}

//Todo: check if the use is an admin

if ( !isset($_FILES['fileUpload']['name']) ) {
    die ("No file provided!");
}

if ( $_FILES['fileUpload']['error'] != UPLOAD_ERR_OK ) {
    die ("Looks like there was an error: {$_FILES['fileUpload']['error']}");
}    

if ($_FILES['fileUpload']['size'] > MAX_ASSET_UPLOAD_SIZE ){
    die ("The file is too large; Max. allowed size is:".MAX_ASSET_UPLOAD_SIZE);
}

$imageFile = ($_FILES['fileUpload']['name']);
$fileExt = pathinfo($imageFile, PATHINFO_EXTENSION);

$validExt = array(    'jpg',    'jpeg',    'png',    'gif');
$validTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif");

if ( !in_array($_FILES['fileUpload']['type'], $validTypes) || !in_array($fileExt, $validExt)) {
    die('Not a valid image type.');
}
  
switch ( $_POST['type'] ){
    case 'mugshot':
        $targetPath = ASSETSYSPATH.'actors/'.$imageFile;
        break;
    case 'thumbnail':
        $targetPath = ASSETSYSPATH.'actors/thumbnail/'.$imageFile;
        break;
    case 'poster':
        $targetPath = ASSETSYSPATH.'episodes/'.$imageFile;
        break;
    case 'logo':
        $targetPath = ASSETSYSPATH.'channels/'.$imageFile;
        break;
    case 'wallpaper':
        $targetPath = ASSETSYSPATH.'wallpaper/'.$imageFile;
        break;
    default:
        die ('Unsupported Asset-Type');
}

if (file_exists($targetPath)) {
    die ( "File {$imageFile} already exists");
}

if ( move_uploaded_file($_FILES['fileUpload']['tmp_name'], $targetPath)) {
    echo $imageFile;
    exit;
} else 
    die ('Upload error');
    
