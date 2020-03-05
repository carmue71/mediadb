<?php

define('SRC_PATH', '/opt/MediaDB/src/');

include_once SRC_PATH.'mediadb/conf.php';
include_once SRC_PATH.'tools/texttools.php';

if ( isset($_GET['file'])){
    $file = $_GET['file'];
} else {
    $file = "default.png";
}

//TODO: check for widht / height

$fullname = "";
$maxWidth = 1000;
$maxHeight = 1000;

$size = isset($_GET['size']) ? $_GET['size']:'XL';

if ( isset($_GET['type'])){
    switch ($_GET['type']){
        case 'logo': //provide a studio logo
            if ( $file != "" ){
                $fullname = ASSETSYSPATH.'channels/'.$file;
            } else {
                $fullname = ASSETSYSPATH.'channels/default.png';
            }
            switch ( $size ){
                case 'XS': $maxWidth = 80; $maxHeight = 50; break;
                case 'S': $maxWidth = 160; $maxHeight = 40; break;
                case 'L': $maxWidth = 600; $maxHeight = 337; break;
                case 'XL': $maxWidth = 1280; $maxHeight = 800; break;
                case 'XXL': $maxWidth = 2400; $maxHeight = 1600; break;
                case 'N':
                default:
                    $maxWidth = 200; $maxHeight = 40; break;
            }//switch
            break;
        case 'mugshot': //picture for the actor
            $fullname = $file != ""?ASSETSYSPATH.'actors/'.$file:"";
            if ( $fullname == "" || !file_exists($fullname) ){
                if ( isset($_GET['gender']) ){
                    switch ( $_GET['gender'] ){
                        case 'F': $fullname = ASSETSYSPATH."actors/default_f.png"; break;
                        case 'M': $fullname = ASSETSYSPATH."actors/default_m.png"; break;
                        //case 'S': $fullname = ASSETSYSPATH."model/default_s.png"; break;
                        default:
                            $fullname = ASSETSYSPATH."actors/default.png";
                    } 
                }
                else 
                    $fullname = ASSETSYSPATH."actors/default.png";
            }
            switch ( $size ){
                case 'XS': $maxWidth = 27; $maxHeight = 41; break;
                case 'S': $maxWidth = 54; $maxHeight = 82; break;
                case 'L': $maxWidth = 337; $maxHeight = 600; break;
                case 'XL': 
                case 'XXL': 
                case 'N':
                default:
                    $maxWidth = 270; $maxHeight = 410; break;
            }
            break;
            
        case 'thumbnail':
            $fullname = $file != ""?ASSETSYSPATH.'actors/thumbnails/'.$file:"";
            if ( $fullname == "" || !file_exists($fullname) ){
                //TODO: randomize and select from size
                $fullname = ASSETSYSPATH."actors/thumbnails/default.png";
            }
            switch ( $size ){
                case 'XS': $maxWidth = 27; $maxHeight = 41; break;
                case 'S': $maxWidth = 54; $maxHeight = 82; break;
                case 'L': $maxWidth = 337; $maxHeight = 600; break;
                case 'XL':
                case 'XXL':
                case 'N':
                default:
                    $maxWidth = 270; $maxHeight = 410; break;
            }
            break;
        case 'poster'://Picture representing a mediaset
            $fullname = $file != ""?ASSETSYSPATH.'episodes/'.$file:"";
            if ( $fullname == "" || !file_exists($fullname) ){
                //TODO: randomize and select from size
                $fullname = ASSETSYSPATH."episodes/default.png";
            }
            //TODO: respect different witdth
                        
            switch ( $size ){
                case 'XS': $maxWidth = 50; $maxHeight = 39; break;
                case 'S': $maxWidth = 120; $maxHeight = 80; break;
                case 'L': $maxWidth = 240; $maxHeight = 170; break;
                case 'XL': $maxWidth = 800; $maxHeight = 600; break;
                case 'XXL': $maxWidth = 1600; $maxHeight = 1200; break;
                case 'N':
                default:
                    $maxWidth = 600; $maxHeight = 337; break;
            }
            break;
        case 'system': //systempictures
            $fullname = $file != ""?ASSETSYSPATH.'system/'.$file:"";
            switch ( $size ){
                case 'XS': $maxWidth = 20; $maxHeight = 20; break;
                default:
                    $maxWidth = 60; $maxHeight = 60; break;
                
            }
            break;
        default:
            switch ( $size ){
                    case 'XS': $maxWidth = 50; $maxHeight = 39; break;
                    case 'S': $maxWidth = 100; $maxHeight = 75; break;
                    case 'L': $maxWidth = 400; $maxHeight = 300; break;
                    case 'XL': $maxWidth = 800; $maxHeight = 450; break;
                    case 'XXL': $maxWidth = 1600; $maxHeight = 900; break;
                    case 'N':
                    default:
                        $maxWidth = 600; $maxHeight = 337; break;
            }//switch
    }//switch
}//fi

$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

if ($ext == 'jpg' || $ext == 'jpeg') {
    $image = imagecreatefromjpeg($fullname);
} elseif ($ext== 'webp' ){
    $image = imagecreatefromwebp($fullname);
} else {
    $image = imagecreatefrompng($fullname);
}

if ( $image ){
    $width = imagesx($image);
    $height = imagesy($image);
    $scale = min($maxWidth/$width, $maxHeight/$height);
    $newWidth = floor($scale * $width);
    $newHeight = floor($scale * $height);
    $tmpImg= imagecreatetruecolor($maxWidth, $maxHeight);
    imagesavealpha($tmpImg, true);
    $trans_colour = imagecolorallocatealpha($tmpImg, 0, 0, 0, 127);
    imagefill($tmpImg, 0, 0, $trans_colour);
    //imagefilledellipse($png, 400, 300, 400, 300, $red);  //for the thumbnails 
            //imagecreate($newWidth, $newHeight);
    imagecopyresized($tmpImg, $image, ($maxWidth-$newWidth)/2,($maxHeight-$newHeight)/2,0,0, $newWidth, $newHeight, $width, $height);
        //imagecopyresampled($tmpImg, $image, 0,0,0,0, $newWidth, $newHeight, $width, $height);
    imagedestroy($image);
    $image = $tmpImg;
} else {
    die ("cannot load {$fullname}");
    $image = imagecreatetruecolor($maxWidth, $maxHeight);
}
//if ( $ext == 'png'){
    header("Content-type: image/png");
    imagepng($image);//, null, 90);
//} else {
 //   header("Content-type: image/jpg");
 //   imagejpeg($image, null, 100);
//}