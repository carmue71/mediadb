<?php
/* update.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Provides serveral ajax reactions
 */

namespace mediadb;

define('SRC_PATH', '/opt/MediaDB/src/');

include_once SRC_PATH.'tools/logging.php';
Logger::$logLevel = MDB_LOG_DEBUG;
Logger::$consoleLevel = MDB_LOG_NONE;

if ( !isset($_POST['what']) ){
    Logger::info("Update.php: What not provided - terminating");
    exit();
}

session_start();
Logger::info('Update.php: Connection to a session');

if ( !isset($_SESSION['login']) || empty($_SESSION['login']) ){
    Logger::warn("Update.php: Update called outside an active session");
    die('Please call this from within a session');
}
  
include_once SRC_PATH.'mediadb/conf.php';
include_once SRC_PATH.'tools/texttools.php';
include_once SRC_PATH.'tools/databasetools.php';

switch($_POST['what']){
    case 'watched':
        Logger::debug("Update.php: Setting episode watched");
        $msid=esc($_POST['msid']);
        $query = 'UPDATE Episode SET Viewed = 1 WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid];
        break;
    case 'progess':
        Logger::debug("Update.php: Saving episode progress");
        $msid=esc($_POST['msid']);
        //$query = 'UPDATE Episode SET Viewed = 1 WHERE ID_Episode = :msid LIMIT 1';
        //$parameters = ['msid'=>$msid];
        exit;
    case 'unwatched':
        Logger::debug("Update.php: Setting episode as unwatched");
        $msid=esc($_POST['msid']);
        $query = 'UPDATE Episode SET Viewed = 0 WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid];
        break;
    case 'rateactor':
        Logger::debug("Update.php: rating actor");
        $rating = esc($_POST['rating']);
        $mid=esc($_POST['mid']);
        $query = 'UPDATE Actor SET Rating = :rating WHERE ID_Actor = :mid LIMIT 1';
        $parameters = ['mid'=>$mid, 'rating'=>$rating];
        break;
        
    case 'rating':
        Logger::debug("Update.php: setting episode rating");
        $rating = esc($_POST['rating']);
        $msid=esc($_POST['msid']);
        $query = 'UPDATE Episode SET Rating = :rating WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid, 'rating'=>$rating];
        break;
        
    case 'deletems':
        Logger::debug("Update.php: deletms episode {$_POST['msid']}, {$_POST['purge']}");
        try {
            return removeEpisode(esc($_POST['msid']), esc($_POST['purge']));
        } catch ( \Exception $ex){
            Logger::error("Update.php: Exction deleting episode {$_POST['msid']}, {$_POST['purge']}:");
            Logger::error("Update.php: " + $ex->getMessage());
            return false;
        }
        
    case 'deleteactor':
        Logger::debug("Update.php: deleting actor");
        $purge = esc($_POST['purge']);
        $mid=esc($_POST['mid']);
        if ( $purge ){
            Logger::debug("Update.php: purching files from actor");
            purgeFilesOfActor($mid);
        } 
        $query = 'DELETE FROM Actor WHERE ID_Actor = :mid LIMIT 1';
        $parameters = ['mid'=>$mid];
        break;
   
    //added 08.04.2018
    case 'incviewed':
        Logger::debug("Update.php: Increasing views for episode");
        $msid=esc($_POST['msid']);
        $query = 'UPDATE Episode SET Viewed = Viewed+1 WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid];
        break;

    //----- add to  watchlist - part 1 -------------
    // finds the last positon of the watchlist and inserts the given episode
    // to finalise, part 2 is called later.
    case 'addtowatchlist':
        Logger::debug("Update.php: Adding episode to watchlist - part 1");
        $msid=esc($_POST['msid']);
        $wid=esc($_POST['wid']);
        $position = findNextPosition($wid);
        $query = 'INSERT INTO C_WatchList_Episode (REF_Episode, REF_WatchList, Position) VALUES (:msid, :wid, :pos)';
        $parameters = ['msid'=>$msid, 'wid'=>$wid, 'pos'=>$position];
        break;
        
    case 'logprogress':
        Logger::debug("Update.php: logging progress");
        $fid=esc($_POST['fid']);
        $progress=esc($_POST['progress']);
        $query = 'UPDATE File SET Progress = :progress WHERE ID_File = :fid LIMIT 1';
        $parameters = ['fid'=>$fid, 'progress'=>$progress];
        break;
        
    case 'removefromwatchlist':
        Logger::debug("Update.php: Removing episode {$_POST['msid']} from watchlist");
        $msid=esc($_POST['msid']);
        $wlid=esc($_POST['wlid']);
        $position=esc($_POST['pos']);
        $query = 'DELETE FROM C_WatchList_Episode WHERE REF_Episode=:msid AND REF_WatchList=:wlid AND Position=:pos LIMIT 1';
        $parameters = ['msid'=>$msid, 'wlid'=>$wlid, 'pos'=>$position];
        break;

    case 'unlink':
        Logger::debug("Update.php: Unlinking episode {$_POST['msid']} from actor {$_POST['mid']}");
        $msid=esc($_POST['msid']);
        $mid=esc($_POST['mid']);
        $query = 'DELETE FROM C_Actor_Episode WHERE REF_Episode=:msid AND REF_ACTOR=:mid LIMIT 1';
        $parameters = ['msid'=>$msid, 'mid'=>$mid];
        break;
    
    case 'deletedevice':
        Logger::debug("Update.php: Removing device {$_POST['devid']}");
        $devid=esc($_POST['devid']);
        $query = 'DELETE FROM Device where ID_DEVICE=:devid LIMIT 1';
        $parameters = ['devid'=>$devid];
        break;
    
    case 'deletefile':
        $retVal = false;
        try {
            Logger::debug("Update.php: Removing / deleting single file {$_POST['fid']} - {$_POST['purge']} ");
            $retVal = deleteFile(esc($_POST['fid']), esc($_POST['purge']));
            Logger::debug("Update.php: deleteFile returned {$retVal} ");
        } catch ( \Exception $e){
            Logger::error("Update.php: Exception: ".$e->getMessage());
        }
        return $retVal;
    default:
        Logger::warn("Update.php: Unknown parameter: {$_POST['what']}");
        die('Unknown parameter');
        exit;
    }
    Logger::debug("Update.php: Connecting to database (1)");
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare($query);
    Logger::debug("Update.php: Query prepared: "+$query);
    if ( $stmt && $stmt->execute($parameters) ){
        $result = 'OK';
        Logger::debug("Update.php: Query {$query} successfully executed");
        
        switch($_POST['what']){
            case 'addtowatchlist':
                Logger::debug("Update.php: Adding episode to watchlist - part2");
                $msid=esc($_POST['msid']);
                $wid=esc($_POST['wid']);
                $position = findNextPosition($wid);
                $query = 'SELECT Title FROM WatchList WHERE ID_WatchList = :wid';
                $parameters = ['wid'=>$wid];
            
                $stmt = $pdo->prepare($query);
                if ( $stmt && $stmt->execute($parameters) ){
                    $title = $stmt->fetch()['Title'];
                } else
                    $title = '';
                echo json_encode(array($result,$title));
                break;
            default: 
                
                echo $result;
        }
    } else {
        Logger::error("Update.php: Error while executing the query: {$query}");
        echo 'Error';
    }
exit;
// end of main switch ####################################################################################

function removeEpisode($msid, $purge){
    Logger::info("Update.php: remvoveEpisode({$msid}, {$purge})");
    
    $pdo = connectToDatabase();
    
    if ( $purge == "true" ){
        Logger::debug("Update.php: removing files");
        //delete poster
        Logger::warning("Update.php: removing Poster not implemented");
        //delete wallpaper
        Logger::warning("Update.php: removing Wallpaper not implemented");
        
        //delete directories
        $episode_query = 'SELECT V.PublisherCode as code, C.Site as site FROM V_EpisodeWithChannel V, Channel C where V.REF_Channel = C.ID_Channel AND V.ID_Episode =  :msid LIMIT 1';
        
        $episode_stmt = $pdo->prepare($episode_query);
        if ( $episode_stmt && $episode_stmt->execute(['msid' => $msid]) ){
            Logger::debug("Update.php: episode query successful");
            $episode = $episode_stmt->fetch();
            if ( $episode ){
                Logger::debug("Update.php: found episode in db");
                //Run through all devices -> if active, try to remove the path
                $devices_query = 'SELECT Path FROM Device';
                $devices_stmt = $pdo->prepare($devices_query);
                if ( $devices_stmt && $devices_stmt->execute()){
                    Logger::debug("Update.php: devices query successful");
                    while ( $dev = $devices_stmt->fetch() ){
                        #Logger::debug("Update.php: checking device {$dev['Path']}");
                        $path = $dev['Path'].'files/';
                        //Check if device is mounted i.e. files exists
                        if ( file_exists($path) ){
                            Logger::debug("Update.php: device is mounted {$path} - trying to delete the episode");
                            $episode_path = $path . $episode['site'] . "/" . $episode['code'] . '/';
                            //TODO: use escapeshellarg()
                            if ( file_exists($episode_path) ){
                                Logger::info("Update.php: Episode '{$episode_path}' found on this device");
                                //realy remove the episode
                                $cmd = "rm -r '{$episode_path}'";
                                Logger::info("Update.php: executing {$cmd}");
                                $output = shell_exec($cmd);
                                Logger::info("Update.php: output: {$output}");
                            } else {
                                Logger::info("Update.php: Episode '{$episode_path}' not found on this device");
                            }
                        } else {
                            Logger::debug("Update.php: device is not mounted - {$path} - does not exist; going to the next device");
                            //Logger::warning("Update.php: {$path} does not exists, maybe device is not mounted or no read permissions");
                        }
                    }//next
                } else {
                    Logger::error("Update.php: cannot get devices list");
                }
            } else {
                Logger::error("Update.php: cannot find episode {$msid}");
            }
        }
        
        
        /*$query = 'SELECT * FROM V_FileWithDevice WHERE REF_Episode = :msid';
        $stmt = $pdo->prepare($query);
        if ( $stmt && $stmt->execute($parameters) ){
            while ( $file = $stmt->fetch() ){
                $devPath = $file['SystemPath'];
                if ( file_exists($devPath.'files/') ){
                    $fullname = $devPath."files/".$file['Path'].$file['Name'];
                    if ( is_writable($fullname) ){
                        Logger::warning("Update.php: Deleting '{$fullname}' from device");
                        //if ( !unlink($fullname) ){
                    } else {
                        Logger::error("Update.php: file '{$fullname}' is not writeable - cannot delete file");
                    }
                } else {
                    Logger::warning("Update.php: device '{$devPath}' not mounted - cannot delete file");
                }
            }
        }*/
    }else {
        Logger::debug("Update.php: deleting episode {$msid}-{$_POST['purge']}");
    }
    $query = 'DELETE FROM Episode WHERE ID_Episode = :msid LIMIT 1';
    Logger::debug("Update.php: Query set to {$query}");
    $parameters = ['msid'=>$msid];
   
    $stmt = $pdo->prepare($query);
    
    if ( $stmt && $stmt->execute($parameters) ){
        $result = 'OK';
        Logger::debug("Update.php: Query {$query} successfully executed");
    }
    print('OK');
    return true;    
}


function purgeFilesOfActor(int $mid){
    Logger::error("Update.php: purge files for actor is not implemented yet");
    //TODO: implement
}

//
function deleteFile(int $fid, String $purge){
    Logger::debug("Update.php: Deleting file {$fid}");
    $pdo = connectToDatabase();
    $success = true;
    $fullname = "";
    if ( $purge == "true" ){
        Logger::debug("Update.php: Deleting file {$fid} from disk");
        $query = "SELECT SystemPath, Path, Name FROM V_FileWithDevice WHERE ID_File=:fid";
        $stmt = $pdo->prepare($query);
        if ( $stmt && $stmt->execute(['fid' => $fid]) ) {
            $file = $stmt->fetch();
            if ( $file != null ){
                $fullname = $file['SystemPath']."files/".escapeshellarg($file['Path']).escapeshellarg($file['Name']);
                if ( is_writable($fullname) ){
                    if ( !unlink($fullname) ){
                        $success = false;
                        Logger::error("Update.php: File ".$fullname." cannot be deleted");
                        Logger::info("Update.php: Pls check if the device is mounted and the user has sufficient permissions!");
                        $msg = array("error"=>"file ".$fullname." cannot be deleted; check if the device is mounted and the user has sufficient permissions!");
                    } 
                } else {
                    Logger::error("Update.php: File ".$fullname." is not writable!");
                    Logger::info("Update.php: Pls check if the device is mounted and the user has sufficient permissions!");
                    $success = false;
                    $msg = array("error"=>"file ".$fullname." cannot be deleted; check if the device is mounted and the user has sufficient permissions!");
                }
            } else {
                Logger::error("Update.php: File with ID {$fid} not found");
                $success = false;
                $msg = array("error"=>"file ".$fid." not found in DB; check if the device is mounted and the user has sufficient permissions!");
            }
        } else {
            Logger::error("Update.php: Error while executing db query");
            $success = false;
            $msg = array("error"=>"file ".$fid." not found in DB; check if the device is mounted and the user has sufficient permissions!");
        }
        if ( $success ){
            Logger::info("Update.php: File {$fullname} successfully deleted from disk");
        }
    } else {
        Logger::debug("Purging is deactivated");
    }
    if ( $success ){
        //remove file from DB
        $query = "DELETE FROM File WHERE ID_File=:fid LIMIT 1";
        $stmt = $pdo->prepare($query);
        if ( $stmt && $stmt->execute(['fid' => $fid]) ) {
            $msg = array("success"=>"file deleted");
            Logger::debug("Update.php: File removed from db");
            exit(true);
        } else {
            Logger::error("Update.php: File {$fid} could not be removed from database with query {$query}");
            $msg = array("error"=>"file ".$fid." cannot be deleted; Some error occured while accessing the database");
        }
    }
    echo json_encode($msg);die;
}

//----- watchList ----------------------------------------------------------------------------------------
function findNextPosition(int $id_watchlist){
    //return 4;
    $query = "SELECT MAX(Position) as Pos FROM C_WatchList_Episode WHERE REF_WatchList=:id";
    //$parameters = ['wid'=>$id_watchlist];
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare($query);
    if ( $stmt && $stmt->execute(['id' => $id_watchlist]) ) {
        $id = $stmt->fetch();
        if ( $id == null ){
            var_dump($query);
            return -1;
        }
        return $id['Pos']+1;
    }
    return 0;   
}