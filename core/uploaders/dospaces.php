<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\uploaders\dospaces: Uploader for DigitalOcean Spaces
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
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter as Adapter;

final class dospaces extends Uploader {
    /**
     * Set up DO Spaces connection to the server
     * 
     * @return void
     */
    public function setup() {
        $client = S3Client::factory([
            'credentials' => [
                'key'    => $this->core->setting('upload_username'),
                'secret' => $this->core->setting('upload_password'),
            ],
            'region' => $this->core->setting('upload_region'),
            'version' => 'latest|version',
            'endpoint' => 'https://' . $this->core->setting('upload_region') . '.digitaloceanspaces.com'
        ]);

        $adapter = new Adapter($client, $this->core->setting('upload_bucket'), $this->core->setting('upload_path'));

        $this->filesystem = new Filesystem($adapter);
    }
}