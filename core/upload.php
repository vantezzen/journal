<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\upload: Handle uploading files after static file generation
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace core;

use \core\uploaders\ftp;

class upload {
    /**
     * Instance of core\core that is connected to this instance
     * 
     * @var \core\core
     */
    public $core;

    /**
     * Constructor
     * 
     * The constructor will save the core instance and register itself on the core instance.
     *
     * @param core $core Instance of the core to connect to
     * @return void
     */
    public function __construct(core $core) {
        $this->core = $core;

        $core->registerComponent('upload', $this);
    }

    /**
     * Uploads all files inside public/ to the server using the chosen Uploader
     * 
     * @return void
     */
    public function upload() {
        // Check if an uploader has been chosen
        if ($this->core->setting('upload_uploader') === 0) {
            return;
        }

        // Get and setup filesystem
        $filesystem = $this->core->component('uploader:' . $this->core->setting('upload_uploader'));
        $filesystem->setup();

        // Upload files to server
        $files = $this->core->component('file')->folderContent('public', false);
        foreach($files as $file) {
            if (is_dir($file)) continue;

            $content = file_get_contents($file);
            $path = preg_replace('/^public\//', '', $file);
            $filesystem->upload($content, $path);
        }
    }
}
