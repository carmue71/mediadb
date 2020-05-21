<?php

/* index.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: main file called when the user starts the mdb 
 */



namespace mediadb;

session_start();

define('SRC_PATH', '/opt/MediaDB/src/');

require_once SRC_PATH.'mediadb/conf.php';
require_once SRC_PATH.'tools/texttools.php';

if ( !isset($_SESSION['login']) || $_SESSION['login'] == null) {
    header('location: '.WWW.'login.php');
    exit();
}


//get the requested path
if ( isset($_SERVER['PATH_INFO']) )
    $pathInfo = $_SERVER['PATH_INFO'];
else 
    $pathInfo = "";

$routes = [    
    '/listepisodes' => ['controller' => 'EpisodeController','method' => 'showAll'],
    '/showepisode' => ['controller' => 'EpisodeController', 'method' => 'show'],
    '/editepisode' => ['controller' => 'EpisodeController', 'method' => 'edit'],
    '/newepisode' => ['controller' => 'EpisodeController', 'method' => 'add'],
    '/movies' => ['controller' => 'EpisodeController', 'method' => 'showMovies'],
    '/showfiles' =>['controller' => 'EpisodeController','method' => 'showfiles'],
    '/showpix' =>['controller' => 'EpisodeController','method' => 'showpix'],    
    '/saveepisode' => ['controller' => 'EpisodeController', 'method' => 'save'],
    '/scanepisode' => ['controller'=> 'EpisodeController', 'method'=>'scan'],
    
    '/showchannel' => ['controller' => 'ChannelController', 'method' => 'show'],
    '/listchannels' => ['controller'=> 'ChannelController', 'method'=>'showAll'],
    '/newchannel' => ['controller'=> 'ChannelController', 'method'=>'add'],
    '/editchannel' => ['controller'=> 'ChannelController', 'method'=>'edit'],
    '/savechannel' => ['controller'=> 'ChannelController', 'method'=>'save'],
    '/filesforchannel'=>['controller'=> 'ChannelController', 'method'=>'showfiles'],
    '/listepisodesforchannel'=>['controller'=> 'ChannelController', 'method'=>'listEpisodesForChannel'],
    '/scanchannel' => ['controller'=> 'ChannelController', 'method'=>'scan'],
    
    '/showwatchlist' => ['controller' => 'WatchListController', 'method' => 'show'],
    '/listwatchlists' => ['controller'=> 'WatchListController', 'method'=>'showAll'],
    '/newwatchlist' => ['controller'=> 'WatchListController', 'method'=>'add'],
    '/editwatchlist' => ['controller'=> 'WatchListController', 'method'=>'edit'],
    '/savewatchlist' => ['controller'=> 'WatchListController', 'method'=>'save'],
    '/listepisodesforwatchlist'=>['controller'=> 'WatchListController', 'method'=>'listContent'],
    '/addmstowatchlist'=>['controller'=> 'WatchListController', 'method'=>'addEpisode'],
    
    '/showactor' => ['controller' => 'ActorController', 'method' => 'show'],
    '/listactors' => ['controller'=> 'ActorController', 'method'=>'showAll'],
    '/editactor' => ['controller'=> 'ActorController', 'method'=>'edit'],
    '/newactor' => ['controller'=> 'ActorController', 'method'=>'add'],
    '/saveactor' => ['controller'=> 'ActorController', 'method'=>'save'],
    '/listepisodesforactor'=>['controller'=> 'ActorController', 'method'=>'listepisodesforactor'],
    '/tweetsfromactor'=>['controller'=> 'ActorController', 'method'=>'tweetsfromactor'],
    '/filesforactor'=>['controller'=> 'ActorController', 'method'=>'showfiles'],
    
    '/listdevices' => ['controller'=> 'DeviceController', 'method'=>'showAll'],
    '/newdevice' => ['controller'=> 'DeviceController', 'method'=>'add'],
    '/editdevice' => ['controller'=> 'DeviceController', 'method'=>'edit'],
    '/savedevice' => ['controller'=> 'DeviceController', 'method'=>'save'],
    '/deletedevice'=> ['controller'=> 'DeviceController', 'method'=>'delete'],
    '/scandevice' => ['controller'=> 'DeviceController', 'method'=>'scan'],
    
    '/showfile' => ['controller' => 'FileController', 'method' => 'show'],
    '/editfile' => ['controller' => 'FileController', 'method' => 'edit'],
    '/listfiles' => ['controller'=> 'FileController', 'method'=>'showAll'],
    
    '/showkeyword' => ['controller' => 'KeywordController', 'method' => 'show'],
    '/listkeywords' => ['controller' => 'KeywordController', 'method' => 'showAll'],
    
    '/search' => ['controller' => 'SearchController', 'method' => 'search'],
    
    //'/axupdateposter'  => ['controller' => 'AjaxController', 'method' => 'updatePoster'],
    
    '/editsettings'  => ['controller' => 'SettingsController', 'method' => 'edit'],
    
    '/listusers' => [ 'controller' => 'UserController', 'method' => 'list' ],
    '/userdetails' => ['controller' => 'UserController','method' => 'detail'],
    '/adduser' => ['controller' => 'UserController','method' => 'add'],
    '/edituser' => ['controller' => 'UserController','method' => 'edit'],
    '/saveuser' => ['controller' => 'UserController','method' => 'save'],
    '/logout' => ['controller' => 'UserController','method' => 'logout']
];

if ( isset($routes[$pathInfo]) && $routes[$pathInfo] == 'login'){
    var_dump($pathInfo);
    route($pathInfo);
    //$_SESSION['username'] = 'charly';
}
if (isset($routes[$pathInfo])) {
    require_once SRC_PATH . 'tools/autoloader.php';
    require_once SRC_PATH . 'mediadb/Container.php';
    $container = new Container();
    $route = $routes[$pathInfo];
    $controller = $container->make($route['controller']);
    $method = $route['method'];
    $controller->$method();
} else {
    $showLogin = false;
    require_once SRC_PATH . 'tools/autoloader.php';
    require_once SRC_PATH . 'mediadb/Container.php';
    $container = new Container();
    $pdo = $container->make("pdo");
    include VIEWPATH . 'welcome.php';
}


