<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * perform_upload.php : Perform file upload in the background
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
// Bootstrap core
require 'dashboard/bootstrap.php';

// End connection to user
// Uploading can take some time, we don't want we users browser to wait
ob_end_clean();
header("Connection: close");
ignore_user_abort();
header("Content-Length: 0");
header("Location: ./");
ob_end_flush();
flush();

$core->component('database')->table('settings')->insert([
    'key' => 'uploading',
    'value' => time()
])->save();

// Regenerate static files
$core->component('upload')->upload();

$core->component('database')->table('settings')->select(['key' => 'uploading'])->delete()->save();