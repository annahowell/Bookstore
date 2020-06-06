<?php

/*
 * This file provides a trivial method to toggle verbose PHP error reporting. It also contains configuration globals
 * used throughout the minimalist MVC framework. Whilst no longer strictly necessary, I utilise DIRECTORY_SEPARATOR to
 * support legacy platforms.
 */

define('DEV', TRUE);

if (DEV)
{
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else
{
    ini_set("display_errors", 0);
}

// Folder globals
define('DS', DIRECTORY_SEPARATOR);
define('APP_DIR', __DIR__ . DS);
define('VIEW_DIR', APP_DIR . 'Views' . DS);
define('URL_SUB_DIR', '');
#define('URL_SUB_DIR', DS . 'bookstore');

    
// Image globals
define('IMAGE_DIR', APP_DIR . '..' . DS . 'images' . DS);
define('PLACEHOLDER_IMG', 'placeholder.png');

// DB conf globals
define('DB_IP',   '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'bookstore');
define('DB_USER', '');
define('DB_PASS', '');
