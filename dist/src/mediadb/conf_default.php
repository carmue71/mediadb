<?php
namespace src\mediadb;
    define('VERSION', 'Version 0.8');

    define('PASSWORD', 'your_secret_password');
    define('DBNAME', 'mediadb');
    define('DBHOST', 'localhost');
    define('DBUSER', 'mediadb');
    
    define('VIEWPATH', SRC_PATH.'mediadb/view/');
    define('LAYOUTPATH', VIEWPATH.'layout/');
    
    define('WWW', '/mediadb/');
    define('CSSPATH', WWW.'css/');
    define('INDEX', WWW.'index.php/');
        
    define('SYSPATH', '/var/www/html/mediadb/');
    define('LIB_PATH', '/opt/mediadb/lib/');
    
    define('ASSETSYSPATH', '/var/lib/mediadb/asset/');
    //define('ASSET _PATH', '/DataMediaDB/Neelix/asset/');
    
    define('DEFAULT_PAGESIZE', 24);
    define('MAXPAGECNT', 9);
    define('COOKIE_LIFETIME', 3600*24*365);
    
    define('SALT', "SomeStrange,,,,KombinationOfCharacters#ääToEncryptYourPasswords#####Don'tlooseit");
    
    #your registration with font-awesome
    define('FONTAWESOME', '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/fontawesome.min.css" integrity="sha256-mM6GZq066j2vkC2ojeFbLCcjVzpsrzyMVUnRnEQ5lGw=" crossorigin="anonymous" />');
    
    define('MAX_ASSET_UPLOAD_SIZE', 20971520); //20MB
    /* Number of navigation buttons (plus 2) displayed at the bottom of a page; 
     * should be an odd number, so that the current page can be in the middle - if possible.
     */
    define ('DEFAULT_SET_PATH', "");
?>