<?php
// Example config file.

// Should contain this. 
header("Content-Type: text/html; charset=UTF-8");

/**
 * Global settings - Individual config. Just nice to have in a config file.
 */
define("DEBUG", false);

if(DEBUG) {
    // set debug settings
    ini_set("display_errors", "1");
    // Report all PHP errors (see changelog)
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL);
} else {
    // deactivate error messages, debug info etc..
    // set debug settings
    ini_set("display_errors", "0");
    // Report all PHP errors (see changelog)
    error_reporting(0);
    ini_set('error_reporting', 0);
}


require_once('SpotifyItem.class.php');
require_once('MetaTune_Album.class.php');
require_once('MetaTune_Artist.class.php');
require_once('MetaTune_Track.class.php');
require_once('MBSimpleXMLElement.class.php');
require_once('MetaTune.class.php');
require_once('MetaTuneException.class.php');

// Added a magic function (autoload) to not have to import all the classes
// if there no use for them.
// function metatune_autoload($class) {
//     $filename = $class . '.class.php';
//     require_once $filename;
// }

//spl_autoload_register('metatune_autoload');
// either you have this autoload-function or you must require/include all files.

