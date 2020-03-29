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
    print("No File provided");
    exit(1);
} 

if ( isset($_GET['type'])){
    $type = $_GET['type'];
} else {
    $type = "text";
}

if ( isset($_GET['filename']))
    $file = $_GET['filename'];
else
    $file = "info.txt"; 
    
if ( !file_exists($path) ){
    print("file not found");
    exit(2);
}

if ( isset($_GET['forcedownload']))
    $forcedownload = true;
else
    $forcedownload = false;

header("Content-Type: $type");
if ( $forcedownload)
    header("Content-Disposition: attachment; filename=\"$file\"");
else 
    header("Content-Disposition: inline; filename=\"$file\"");

    header('Content-Length: ' . filesize($path));
//readfile($path);
passthru("/bin/cat $path");
    

exit(0);

