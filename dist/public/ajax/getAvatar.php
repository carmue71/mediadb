<?php
/* getAvatar.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: provides at runtime an avatar for the current user
 */


if ( isset($_GET['size'])){
    switch ( $_GET['size']){
        case 'XS': $maxWidth = 16; $maxHeight = 16; break;
        case 'S': $maxWidth = 32; $maxHeight = 32; break;
        case 'L': $maxWidth = 128; $maxHeight = 128; break;
        case 'XL': $maxWidth = 256; $maxHeight = 256; break;
        case 'XXL': $maxWidth = 512; $maxHeight = 512; break;
        case 'N': 
        default:
            $maxWidth = 64; $maxHeight = 64; break;
    }
} else {
    $maxWidth = 1000;
    $maxHeight = 1000;
}

if ( isset($_GET['file'])){
    $file = $_GET['file'];
} else { 
    $file = "avatar_default.png";
}

$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

//TODO: allow webp as well
if ($ext == 'jpg' || $ext == 'jpeg') { 
    $image = imagecreatefromjpeg(ASSET_DIRECTORY.'/avatar/' . $file);
} else {
    $image = imagecreatefrompng(ASSET_DIRECTORY.'/avatar/' . $file);
}
        

if ( $image ){
    $width = imagesx($image);
    $height = imagesy($image);
    $scale = min($maxWidth/$width, $maxHeight/$height);
    if ( $scale < 1 ){
            $newWidth = floor($scale * $width);
            $newHeight = floor($scale * $height);
        $tmpImg= //imagecreatetruecolor($newWidth, $newHeight);
        imagecreate($newWidth, $newHeight);
        imagecopyresized($tmpImg, $image, 0,0,0,0, $newWidth, $newHeight, $width, $height);
        //imagecopyresampled($tmpImg, $image, 0,0,0,0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);
        $image = $tmpImg;
    }
} else {
    //TODO: create error image
}
header("Content-type: image/png");
//imagejpeg($image, null, 90);
imagepng($image);//, null, 90);