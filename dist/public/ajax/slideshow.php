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
if ( isset($_POST['pos'])){
    $pos = $_POST['pos'];
} else {
    $pos = 0;
}

//check parameters
if ( isset($_POST['random'])){
    $random = $_POST['random'];
} else {
    $random = false;
}

$pdo = connectToDatabase();
if ( !$pdo ){
    die('cannot connect to database - pls check!');
}

//$id = $_SESSION['filequery_id'];
$filter = buildFilter();
$fileOrder = $_SESSION['filequery_order'];


$cnt = getFileCount($pdo, $filter);
if ( $cnt < 1 ){
    die ('no files found');
}

if ( $random ) {
    $pos = rand(0, $cnt-1);
} else if ( $pos >= $cnt){
    $pos = 0;
} else if ( $pos < 0 ){
    $pos = $cnt-1;
}

$result = getFile($pdo, $pos, $filter, $fileOrder);
echo json_encode($result);
exit();

function getFileCount($pdo, $filter){
    try {
        $query = "SELECT COUNT(*) AS Number FROM V_FileWithDevice WHERE {$filter}";
        $result = $pdo->query($query);
        return $result->fetch()['Number'];
    } catch (PDOException $ex) {
        print "Exception: <br />";
        var_dump($ex);
        return -1;
    }
}

function getFile($pdo, int $pos, $filter, $orderBy){
    $query = "SELECT * FROM V_FileWithDevice WHERE {$filter}";
    
    if (isset($orderBy) && $orderBy != "") {
        $query = $query . " ORDER BY {$orderBy}";
    }
    
    $query = $query . " LIMIT {$pos}, 1";
    
    try {
        $result = $pdo->query($query);
        if ( $result ){
            $file = $result->fetch();
            $title = ($file['Title']!="")?$file['Title']:$file['Name'];
            
            return array(
                    'id' => $file['ID_File'],
                    'title' => $title,
                    'path' => $file['SystemPath'].'files/'.$file['Path'].$file['Name'],
                    'comment'=> $file['Comment'],
                    'keywords'=> $file['Keywords'],
                    'rating'=>$file['Rating'],
                    'filetype'=>$file['REF_Filetype'],
                    'fileinfo'=>$file['FileInfo'],
                    'progress'=>$file['Progress'],
                    'pos' => $pos
            );
        }
        print "<pre>";
        var_dump($query);
        var_dump($this->pdo->errorCode());
        var_dump($this->pdo->errorInfo());
        print "</pre>";
    } catch (PDOException $ex) {
        print "Exception: <br />";
        var_dump($ex);
        return "Error";
    }
}

function buildFilter(){
    $filter = "";
    
    if ( isset($_POST['msid']) ){
        $msid = $_POST['msid'];
        $filter = " REF_Episode = {$msid} "; 
    } else if ( isset($_POST['modelid']) ){
        $modelid = $_POST['modelid'];
        $filter = " REF_Episode IN (SELECT REF_Episode FROM C_Actor_Episode WHERE REF_Actor = {$modelid}) ";
    } else if ( isset($_POST['studioid']) ){
        $studioid = $_POST['studioid'];
        $filter = " REF_Episode IN (SELECT ID_Episode FROM Episode WHERE REF_Channel = {$studioid}) ";
    } else {
        die ('Neither episode nor actor nor provided!');
    }
    
    if ( isset ($_SESSION['filequery_filter']) && $_SESSION['filequery_filter'] != '' ){
        $filter = $filter." AND ".$_SESSION['filequery_filter'];
    }
    
    return $filter;
}