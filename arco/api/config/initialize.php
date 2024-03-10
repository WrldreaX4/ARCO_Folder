<?php

//for location/include purposes, you can just copy this content and change the second line if you use a different server
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . 'xampp'.DS.'htdocs'.DS.'arco2'.DS.'arco'.DS.'api');


//this is where you finally initialize all of your files
defined('CONFIG_PATH') ? null : define('CONFIG_PATH', SITE_ROOT.DS.'config');
defined('MODULES_PATH') ? null : define('MODULES_PATH', SITE_ROOT.DS.'modules');


//load the config file first
require_once(CONFIG_PATH.DS.'database.php');


//load the core classes
require_once(MODULES_PATH.DS.'get.php');
require_once(MODULES_PATH.DS.'post.php');








?>