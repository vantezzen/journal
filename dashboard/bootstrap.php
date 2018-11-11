<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * dashboard/bootstrap: Bootstrap core
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
// Composer autoload
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    echo "Could not find 'vendor/autoload.php'. Please make sure you have executed 'composer install' to install dependencies and create the autoloader.";
    exit();
}

// Use all core parts
use core\core;
use core\generator;
use core\pages;
use core\database;
use core\dashboard;
use core\convert;
use core\url;
use core\escape;
use core\comments;
use core\update;
use core\file;
use core\upload;
use core\intelliformat;

use core\uploaders\ftp;
use core\uploaders\sftp;
use core\uploaders\s3;
use core\uploaders\dospaces;

// Create new core instance
$core = new core();

// Attach other core components
new database($core);
new generator($core);
new pages($core);
new dashboard($core);
new convert($core);
new url($core);
new escape($core);
new comments($core);
new update($core);
new file($core);
new intelliformat($core);
new upload($core);

// Uploaders
new ftp($core);
new sftp($core);
new s3($core);
new dospaces($core);
