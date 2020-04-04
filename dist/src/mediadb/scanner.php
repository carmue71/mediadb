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


$container = new Container();
$deviceRepository = $container->make('DeviceRepository');
$episodeRepository = $container->make('EpisodeRepository');

//TODO: check if loglevel is set
//TODO: check if cleanup is set
//TODO: check and set semaphore 
//TODO: display semaphore in device-list

print "\nChecking Decoration of the Episodes\n";

//TODO: Check options if this should happen
$episodeRepository->checkDecoration(1);

if ( isset( $argv[1]) ){
    $id = intval($argv[1]);
    $device = $deviceRepository->find($id);
    if ( isset($device)){
        print "\nScanning device {$id}:\n";
        print "\tRemoving files {$id}:\n";
        $deviceRepository->removeMissingFiles($device, 1);
        print "\tScanning for directories and files {$id}:\n";
        $deviceRepository->scan($device, true,2);
    }
    else { 
        print "\n****************************************************";
        print "\nERROR: cannot find the device {$id} in Database";
        print "\nPlease Check!!!";
        print "\n****************************************************";
        die();
    }
}  else {
    print "\nScan all availlable devices\n" ;
    $devices = $deviceRepository->getAll();
    foreach ($devices as $device){
        if ( isset($device)){

            if ( $device->isActive() ){
                print "\nScanning device {$device->Name}:\n";
                print "\tRemoving files:\n";
                
                $deviceRepository->removeMissingFiles($device, 1);
                
                print "\tScanning for directories and files:\n";
                $deviceRepository->scan($device, true, 1);
            }
            else 
                print("\nDevice {$device->Name} seems to be unavaillable - ignoring it for now.\n\n");
        }
    }
}
    
print "\n\n\nScan finished!\n";
//TODO: remove Semaphore

function connectToDatabase(){
    try {
        $pdo = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME.';charset=utf8', DBUSER,  PASSWORD);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (PDOException $e){
        print "Cannot connect to database - please check";
        die();
    }
}

?>