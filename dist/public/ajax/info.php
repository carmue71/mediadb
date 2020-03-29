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
    die ('no file-id provided!');
}

//connect to database
$pdo = connectToDatabase();
if ( !$pdo ){
    die('cannot connect to database - pls check!');
}

$file = getFile($pdo, $fid);
if ( !isOnline($file['DevPath']) ){
    createErrorThumb("Device is not online / path not found");
    exit;
}

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