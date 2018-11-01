<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * generate_static: Interface for theme_watch to trigger regeneration of static files
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
// Only run in CLI mode
if (php_sapi_name() !== "cli") {
    echo "Oops! This page should only be accessed through the CLI";
    exit();
}

// Bootstrap core
require 'dashboard/bootstrap.php';

// Temporarily change theme if supplied via command line argument
if (isset($argv[1]) && file_exists('themes/' . $argv[1])) {
    $core->settings['theme'] = $argv[1];
}

// Regenerate static files
$core->component('convert')->all();