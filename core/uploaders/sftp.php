<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\uploaders\sftp: Uploader for SFTP
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
use League\Flysystem\Sftp\SftpAdapter as Adapter;

final class sftp extends Uploader {
    /**
     * Set up SFTP connection to the server
     * 
     * @return void
     */
    public function setup() {
        $port = empty($this->core->setting('upload_port')) ? 22 : $this->core->setting('upload_port');

        $this->filesystem = new Filesystem(new Adapter([
            'host' => $this->core->setting('upload_server'),
            'username' => $this->core->setting('upload_username'),
            'password' => $this->core->setting('upload_password'),
            'port' => $port,
            'root' => $this->core->setting('upload_path'),
        ]));
    }
}