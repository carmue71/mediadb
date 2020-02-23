<?php
define('SRC_PATH', '/opt/MediaDB/src/');

#require SRC_PATH.'mediadb/conf.php';
require '../mediadb/conf_default.php';
if ( isset( $argv[1]) ){
    print( md5(SALT.$argv[1])."\n" );
} else {
    print("Please provide a cleartext password\n");
}
?>