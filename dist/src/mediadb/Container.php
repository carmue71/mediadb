<?php
/* Container.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Creates the necessary objects for mediadb
 */


namespace mediadb;

use mediadb\controller\DeviceController;
use mediadb\controller\FileController;
use mediadb\controller\KeywordController;
use mediadb\controller\EpisodeController;
use mediadb\controller\ActorController;
use mediadb\controller\SearchController;
use mediadb\controller\SettingsController;
use mediadb\controller\ChannelController;
use mediadb\controller\UserController;
use mediadb\controller\WatchListController;
use mediadb\repository\DeviceRepository;
use mediadb\repository\FileRepository;
use mediadb\repository\KeywordRepository;
use mediadb\repository\EpisodeRepository;
use mediadb\repository\ActorRepository;
use mediadb\repository\SettingsRepository;
use mediadb\repository\ChannelRepository;
use mediadb\repository\UserRepository;
use mediadb\repository\WatchListRepository;


include_once SRC_PATH.'tools/databasetools.php';

class Container{
    private $receipts = [];
    private $instances = [];
    
    public function __construct()
    { 
        $this->receipts = [
            'UserController' => function(){ return new UserController($this->make('UserRepository')); },
            'UserRepository' => function(){ return new UserRepository($this->make('pdo')); },
            'EpisodeController' => function(){ return new EpisodeController($this->make('EpisodeRepository'), $this->make('ActorRepository'), $this->make('FileRepository')); },
            'EpisodeRepository' => function(){ return new EpisodeRepository($this->make("pdo")); },
            
            'ChannelController' => function(){ return new ChannelController($this->make('ChannelRepository'), $this->make('EpisodeRepository'), 
                $this->make('ActorRepository'), $this->make('FileRepository')); },
            'ChannelRepository' => function(){ return new ChannelRepository($this->make("pdo")); },
            
            'ActorController' => function(){ return new ActorController($this->make('ActorRepository'), $this->make('EpisodeRepository'), $this->make('FileRepository')); },
            'ActorRepository' => function(){ return new ActorRepository($this->make("pdo")); },
            
            'DeviceController' => function(){ return new DeviceController($this->make('DeviceRepository')); },
            'DeviceRepository' => function(){ return new DeviceRepository($this->make("pdo"), $this->make('EpisodeRepository'), $this->make('FileRepository')); },
            
            'FileController' => function(){ return new FileController($this->make('FileRepository')); },
            'FileRepository' => function(){ return new FileRepository($this->make("pdo")); },
            
            'KeywordController' => function(){ return new KeywordController($this->make('KeywordRepository')); },
            'KeywordRepository' => function(){ return new KeywordRepository($this->make("pdo")); },
            
            'SearchController' => function(){ return new SearchController($this->make('EpisodeRepository'), $this->make('ActorRepository')); },
            
            'SettingsController' => function(){ return new SettingsController($this->make('SettingsRepository')); },
            'SettingsRepository' => function(){ return new SettingsRepository($this->make("pdo")); },
            
            'WatchListController' => function(){ return new WatchListController($this->make('WatchListRepository'), 
                $this->make('EpisodeRepository')); },
            'WatchListRepository' => function(){ return new WatchListRepository($this->make("pdo")); },
                
                   
            'pdo'                => function(){ return connectToDatabase(); }
        ];
    }
    
    public function make($name)
    {
        if (!empty($this->instances[$name]))
        {
            return $this->instances[$name];
        }
     
        if (isset($this->receipts[$name])) {
            $this->instances[$name] = $this->receipts[$name]();
            
        } else {
            print "Error: Cannot create: {$name}<br/>";
            die();
        }
        
        return $this->instances[$name];
    }
    
    
}