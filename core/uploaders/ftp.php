<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\uploaders\ftp: Uploader for FTP
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace core\uploaders;

use core\core;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;

final class ftp extends Uploader {
    /**
     * Set up FTP connection to the server
     * 
     * @return void
     */
    public function setup() {
        $port = empty($this->core->setting('upload_port')) ? 21 : $this->core->setting('upload_port');

        $this->filesystem = new Filesystem(new Adapter([
            'host' => $this->core->setting('upload_server'),
            'username' => $this->core->setting('upload_username'),
            'password' => $this->core->setting('upload_password'),
        
            'port' => $port,
            'root' => $this->core->setting('upload_path'),
            'passive' => true,
        ]));
    }
}