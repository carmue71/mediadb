<?php
namespace mediadb;

session_start();

// allready logged in - don't bother
if (isset($_SESSION['login']) && $_SESSION['login'] != null) {
    header('location: /mediadb/index.php');
    exit();
}

define('SRC_PATH', '/opt/MediaDB/src/');

require_once SRC_PATH . 'mediadb/conf.php';
require_once SRC_PATH . 'tools/texttools.php';
require_once SRC_PATH . 'tools/autoloader.php';
require_once SRC_PATH . 'mediadb/Container.php';

// otherwise check username and password
if (isset($_POST['login']) && $_POST['login'] != "") {
//if ( empty($_POST['login']) ){
    $login = $_POST['login'];
    $password = $_POST['password'];
    $_SESSION['login'] = $login;
    
    // check username and password
    $container = new Container();
    $userRepository = $container->make('UserRepository');
    $userid = $userRepository->verifyUser($login, $password);
    if ($userid >= 0) {
        // TODO: log the attempt
        $_SESSION['userid'] = $userid;
        $userRepository->checkhistory($userid);
        header('location: index.php');
        exit();
    } else {
        // TODO: log the attempt
        $errorMessage="<strong>Error</strong> Username or Password are wrong!"
            ."Please contact your administrator if necessary!<br />";
                #."Your ip address ".getAddress()." will be added to the logfile!";
        include VIEWPATH."fragments/alert.php";
    }
}

$showLogin = true;
include VIEWPATH . 'welcome.php';

$_SESSION['login'] = "";

function getAddress(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>