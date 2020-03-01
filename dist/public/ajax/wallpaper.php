<?php
//TODO: randomize and select from size
define('SRC_PATH', '/opt/MediaDB/src/');

include_once SRC_PATH.'mediadb/conf.php';
include_once SRC_PATH.'tools/texttools.php';

if ( isset($_GET['file'])){
    $file = $_GET['file'];
} else {
    $file = "default.png";
}

$fullname = $file != ""?ASSETSYSPATH.'wallpaper/'.$file:"";
if ( $fullname == "" || !file_exists($fullname) ){
    $fullname = ASSETSYSPATH."wallpaper/default.png";
}
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

if ($ext == 'jpg' || $ext == 'jpeg') {
    $image = imagecreatefromjpeg($fullname);
}elseif ( $ext == 'webp' ) {
    $image = imagecreatefromwebp($fullname);
}else {
    $image = imagecreatefrompng($fullname);
}
        
if ( $image == null){
    die ('Error: coult not create the requested image '.$fullname);
}

if ( $ext == 'png'){
    header("Content-type: image/png");
    imagepng($image);//, null, 90);
} elseif( $ext == 'webp' ){
    header("Content-type: image/webp");
    imagewebp($image, null, 100);
} else {
    header("Content-type: image/jpg");
    imagejpeg($image, null, 100);
}

exit;