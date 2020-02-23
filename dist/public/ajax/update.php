<?php
/* update.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Provides serveral ajax reactions
 */

namespace mediadb;

define('SRC_PATH', '/opt/MediaDB/src/');

if ( !isset($_POST['what']) )
    exit();

session_start();

if ( !isset($_SESSION['login']) || empty($_SESSION['login']) )
    die('Please call this from within a session');
  
include_once SRC_PATH.'mediadb/conf.php';
include_once SRC_PATH.'tools/texttools.php';
include_once SRC_PATH.'tools/databasetools.php';
    
switch($_POST['what']){
    case 'watched':
        $msid=esc($_POST['msid']);
        $query = 'UPDATE Episode SET Viewed = 1 WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid];
        break;
    case 'progess':
        $msid=esc($_POST['msid']);
        //$query = 'UPDATE Episode SET Viewed = 1 WHERE ID_Episode = :msid LIMIT 1';
        //$parameters = ['msid'=>$msid];
        exit;
    case 'unwatched':
        $msid=esc($_POST['msid']);
        $query = 'UPDATE Episode SET Viewed = 0 WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid];
        break;
    case 'rateactor':
        $rating = esc($_POST['rating']);
        $mid=esc($_POST['mid']);
        $query = 'UPDATE Actor SET Rating = :rating WHERE ID_Actor = :mid LIMIT 1';
        $parameters = ['mid'=>$mid, 'rating'=>$rating];
        break;
        
    case 'rating':
        $rating = esc($_POST['rating']);
        $msid=esc($_POST['msid']);
        $query = 'UPDATE Episode SET Rating = :rating WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid, 'rating'=>$rating];
        break;
    case 'deletems':
        $purge = esc($_POST['purge']);
        $msid=esc($_POST['msid']);
        if ( $purge ){ 
            purgeFilesOfEpisode($msid);
        }
        $query = 'DELETE FROM Episode WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid];
        break; 
        
    case 'deleteactor':
        $purge = esc($_POST['purge']);
        $mid=esc($_POST['mid']);
        if ( $purge ){
            purgeFilesOfActor($mid);
        }
        $query = 'DELETE FROM Actor WHERE ID_Actor = :mid LIMIT 1';
        $parameters = ['mid'=>$mid];
        break;
   
    //added 08.04.2018
    case 'incviewed':
        $msid=esc($_POST['msid']);
        $query = 'UPDATE Episode SET Viewed = Viewed+1 WHERE ID_Episode = :msid LIMIT 1';
        $parameters = ['msid'=>$msid];
        break;

    //----- add to  watchlist - part 1 -------------
    // finds the last positon of the watchlist and inserts the given episode
    // to finalise, part 2 is called later.
    case 'addtowatchlist':
        $msid=esc($_POST['msid']);
        $wid=esc($_POST['wid']);
        $position = findNextPosition($wid);
        $query = 'INSERT INTO C_WatchList_Episode (REF_Episode, REF_WatchList, Position) VALUES (:msid, :wid, :pos)';
        $parameters = ['msid'=>$msid, 'wid'=>$wid, 'pos'=>$position];
        break;
        
    case 'logprogress':
        $fid=esc($_POST['fid']);
        $progress=esc($_POST['progress']);
        $query = 'UPDATE File SET Progress = :progress WHERE ID_File = :fid LIMIT 1';
        $parameters = ['fid'=>$fid, 'progress'=>$progress];
        break;
        
    case 'removefromwatchlist':
        $msid=esc($_POST['msid']);
        $wlid=esc($_POST['wlid']);
        $position=esc($_POST['pos']);
        $query = 'DELETE FROM C_WatchList_Episode WHERE REF_Episode=:msid AND REF_WatchList=:wlid AND Position=:pos LIMIT 1';
        $parameters = ['msid'=>$msid, 'wlid'=>$wlid, 'pos'=>$position];
        break;

    case 'unlink':
        $msid=esc($_POST['msid']);
        $mid=esc($_POST['mid']);
        $query = 'DELETE FROM C_Actor_Episode WHERE REF_Episode=:msid AND REF_MODEL=:mid LIMIT 1';
        $parameters = ['msid'=>$msid, 'mid'=>$mid];
        break;
    
    case 'deletedevice':
        $devid=esc($_POST['devid']);
        $query = 'DELETE FROM Device where ID_DEVICE=:devid LIMIT 1';
        $parameters = ['devid'=>$devid];
        break;
    
    case 'deletefile':
        return deleteFile(esc($_POST['fid']), esc($_POST['purge']));
    
    default:
        die('Unknown parameter');
        exit;
    }

    $pdo = connectToDatabase();
    $stmt = $pdo->prepare($query);
    if ( $stmt && $stmt->execute($parameters) ){
        $result = 'OK';
    
        switch($_POST['what']){
            case 'addtowatchlist':
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
    } else
        echo 'Error';
exit;
// end of main switch ####################################################################################

//----- filehandling -------------------------------------------------------------------------------------
function purgeFilesOfEpisode(int $msid){
    //delete poster
    
    //delete wallpaper
    
    //delete files
    $query = 'SELECT * FROM V_... WHERE REF_Episode = :msid';
    
    //TODO: Implement
}

function purgeFilesOfActor(int $mid){
    //TODO: implement
}

//
function deleteFile(int $fid, String $purge){
    $pdo = connectToDatabase();
    $success = true;
    if ( $purge == "PURGE" ){
        $query = "SELECT SystemPath, Path, Name FROM V_FileWithDevice WHERE ID_File=:fid";
        $stmt = $pdo->prepare($query);
        if ( $stmt && $stmt->execute(['fid' => $fid]) ) {
            $file = $stmt->fetch();
            if ( $file != null ){
                $fullname = $file['SystemPath']."files/".$file['Path'].$file['Name'];
                if ( is_writable($fullname) ){
                    if ( !unlink($fullname) ){
                        $success = false;
                        $msg = array("error"=>"file ".$fullname." cannot be deleted; check if the device is mounted and the user has sufficient permissions!");
                    } 
                } else {
                    $success = false;
                    $msg = array("error"=>"file ".$fullname." cannot be deleted; check if the device is mounted and the user has sufficient permissions!");
                }
            }
        }
    }
    if ( $success ){
        //remove file from DB
        $query = "DELETE FROM File WHERE ID_File=:fid LIMIT 1";
        $stmt = $pdo->prepare($query);
        if ( $stmt && $stmt->execute(['fid' => $fid]) ) {
            $msg = array("success"=>"file deleted");
            exit(true);
        } else {
            $msg = array("error"=>"file ".$fullname." cannot be deleted; Some error occured while accessing the database");
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