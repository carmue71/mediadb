<?php
/* scanner.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: command line scanner - searches for new media files
 *          Either run manually or via cron
 */

namespace mediadb;

use \PDO;
use \PDOException;

define('SRC_PATH', '/opt/MediaDB/src/');

require_once 'conf.php';
require_once 'model/AbstractModel.php';
require_once 'model/Device.php';
require_once 'model/File.php';
require_once 'model/Episode.php';
require_once 'model/Channel.php';

require_once 'repository/AbstractRepository.php';
require_once 'repository/DeviceRepository.php';
require_once 'repository/ChannelRepository.php';
require_once 'repository/EpisodeRepository.php';
require_once 'repository/FileRepository.php';
require_once 'Container.php';

\mediadb\Logger::info("scanner.php: Loading Scanner *******************************************");

$container = new Container();
$deviceRepository = $container->make('DeviceRepository');
$episodeRepository = $container->make('EpisodeRepository');


$opt = parseArguments($argc, $argv);

$deviceRepository->options['scan']['refreshFileInfo'] = false;
$deviceRepository->options['scan']['checkPoster'] = $opt["checkposter"];
$deviceRepository->options['scan']['checkWallpaper'] = $opt["checkwallaper"];

$deviceRepository->scanStatistics['devices'] = 0;
$deviceRepository->scanStatistics['episodes']['new'] = 0;
$deviceRepository->scanStatistics['episodes']['updated'] = 0;
$deviceRepository->scanStatistics['files']['new'] = 0;
$deviceRepository->scanStatistics['files']['updated'] = 0;
$deviceRepository->scanStatistics['files']['removed'] = 0;
$deviceRepository->scanStatistics['errors']['unknownChannels'] = 0;
$deviceRepository->scanStatistics['errors']['wrongPlacedFiles'] = 0;
$deviceRepository->scanStatistics['errors']['DBErrors'] = 0;

/* **** check decoration ************************************/
if ( $opt['checkDeco'] ){
    \mediadb\Logger::info("scanner.php: Checking Decoration of the Episodes");
    $episodeRepository->checkDecoration();
}

if ( $opt['device'] > 0 ){
    \mediadb\Logger::debug("scanner.php: Scanning single device!");
    $deviceRepository->scanDevice($device = $deviceRepository->find($opt['device']), $opt['filesIgnore'],  
        $opt['filesOnly'], $opt['episodesOnly'], $opt['episode'], $opt['channel']);
}  else {
    \mediadb\Logger::debug("scanner.php: Scanning all availlable devices");
    $devices = $deviceRepository->getAll();
    foreach ($devices as $device){
        if (isset($device)) {
            #\mediadb\Logger::debug("scanner.php: scan started on {$device->Name}!------------------");
            $deviceRepository->scanDevice($device, $opt['filesIgnore'], 
                $opt['filesOnly'], $opt['episodesOnly'], $opt['episode'], $opt['channel']);
            #\mediadb\Logger::debug("scanner.php: scan finished on {$device->Name}!---------------");
        } else {
            \mediadb\Logger::error("scanner.php: device not defined!"); 
        }
    }
    $deviceRepository->showStatistics(true);
}
\mediadb\Logger::info("scanner.php: Scan finished!");



//----- connectToDatabase ---------------------------------------------------------------------------------
function connectToDatabase(){
    try {
        $pdo = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME.';charset=utf8', DBUSER,  PASSWORD);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (PDOException $e){
        \mediadb\Logger::error("scanner.php: Cannot connect to database - please check");
        die();
    }
}

function parseArguments($count, $v){
    //Options to the default
    $opt['loglevel'] = 2; //INFO
    $opt['device'] = -1; //all devices
    $opt['filesOnly'] = false; // search only for files, ignore new episodes
    $opt['episodesOnly'] = false;
    $opt['filesIgnore'] = false;
    $opt['checkDeco'] = true; // check decoration at start
    $opt['episode'] = -1; // all episodes
    $opt['channel'] = -1; //all channels
    
    $opt["checkposter"] = 1; //only check poster for new episodes
    $opt["checkwallaper"] = 1; // only check wallpaper for new episodes
    $opt["fileinfo"] = 1;
    
    if ( $count == 1 ){
        \mediadb\Logger::debug("scanner.php: No Arguments provided - using default");
        return $opt;
    }
    $long_ops = array("device::", "channel::", "episode::","loglevel::","checkdeco", "verbose", "quiet", "newepisodes", "newfiles", 
        "existingfiles", 
        "noposter", //do not try to set posters 
        "nowallaper", //do not try to set wallpaper
        "nodeco", //neither poster nor wallpaper
        "forceposter", //check poster also for exisiting sets without poster
        "forcewallpaper", //dto wallpaper
        "forcedeco", //poster and wallpaper
        "nofileinfo",
        "forcefileinfo"
    );
    $options = getopt("d::c::e::l::i::ovqnfx",$long_ops);
       //deco
    if ( isset($options['o']) || isset($options['checkdeco']) ){
        $opt['checkDeco'] = false; // don't check decoration at start
    }
    
    //new episodes
    if ( isset($options['n']) || isset($options['newepisodes']) ){
        $opt['episodesOnly'] = true; 
    }
    
    //new files
    if ( isset($options['f']) || isset($options['newfiles']) ){
        $opt['filesOnly'] = true;
    }
    
    if ( isset($options['x']) || isset($options['existingfiles']) ){
        $opt['filesIgnore'] = true;
    }
    
    //device 
    if ( isset( $options['d']) ){
        $opt['device'] = intval($options['d']); // only check this device
    } else if ( isset( $options['device']) ){
        $opt['device'] = intval($options['device']); // only check this device
    }
    
    
    //channel
    if ( isset( $options['channel']) ){
        $opt['channel'] = intval($options['channel']); // only check this device
    }
    
    //episode
    if ( isset( $options['episode']) ){
        $opt['episode'] = intval($options['episode']); // only check this device
    }
        
    //loglevel
    if ( isset( $options['l']) ){
        $opt['loglevel'] = intval($options['l']); // only check this device
    } else if ( isset( $options['v']) ){
        $opt['loglevel'] = 2; // only check this device
    } else if ( isset( $options['q']) ){
        $opt['loglevel'] = 0; // only check this device
    } else if ( isset( $options['loglevel']) ){
        $opt['loglevel'] = intval($options['loglevel']); // only check this device
    } else if ( isset( $options['verbose']) ){
        $opt['loglevel'] = 2; // only check this device
    } else if ( isset( $options['quiet']) ){
        $opt['loglevel'] = 0; // only check this device
    }
    
    //decoration
    if ( isset( $options['noposter']) )
        $opt["checkposter"]=0; // do not try to set posters
    
    if ( isset( $options['nowallaper']) )
        $opt["checkwallaper"] = 0; // do not try to set wallpaper
    
    if ( isset( $options['nodeco']) ){
        $opt["checkposter"] = 0;
        $opt["checkwallaper"] = 0; // do not try to set wallpaper
    }
    if ( isset( $options['forceposter']) )
        $opt["checkposter"] = 2; //always check poster
    
    if ( isset( $options['forcewallpaper']) )
        $opt["checkwallaper"] = 2; 
    
    if ( isset( $options['forcedeco']) ){
        $opt["checkposter"] = 2;
        $opt["checkwallaper"] = 2; 
    }
    
    if ( isset( $options['i']) ){
        $x = intval($options['i']);
        if (0 <= $x && $x <= 2)
            $opt['fileinfo'] = $x;
        else 
            $opt['fileinfo'] = 1;
    } elseif ( isset( $options['forcefileinfo']) ){
        $opt['fileinfo'] = 2;
    } elseif ( isset( $options['nofileinfo']) ){
        $opt['fileinfo'] = 0;
    }
        
    \mediadb\Logger::debug("scanner.php: ".json_encode($opt));
    return $opt;
}

?>