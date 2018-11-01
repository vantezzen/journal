<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\uploaders\Uploader: Abstract class for Journal uploaders
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

abstract class Uploader implements UploaderInterface {
    /**
     * Instance of core\core that is connected to this instance
     * 
     * @var \core\core
     */
    public $core;

    /**
     * Filesystem used for uploading files
     * 
     * @var Flysystem_Adapter
     */
    public $filesystem;
    
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

        // Get name of current uploader using class name
        $class = get_class($this);
        // Remove namespace from class name
        $class = explode('\\', $class);
        $class = end($class);

        $core->registerComponent('uploader:' . $class, $this);
    }

    /**
     * Set up flysystem filesystem
     * 
     * Set up the flysystem adapter and filesystem for uploading, then set $this->filesystem
     * to the set up filesystem
     * 
     * @return void
     */
    abstract public function setup();

    /**
     * Upload a file to the server using given file content and path.
     * 
     * This method should also able to overwrite the file if it already exists, e.g. by deleting beforehand
     * 
     * @param string $content Content of the file being uploaded
     * @param string $path Path to the file
     * @return void
     */
    public function upload(string $content, string $path) {
        if ($this->filesystem->has($path)) {
            $this->filesystem->update($path, $content);
        } else {
            $this->filesystem->write($path, $content);
        }
    }

    /**
     * Delete a file with a given absolute path from the server
     * 
     * This method should be able to handle if the given file does not exist
     * 
     * @param string $path Path to the file
     * @return void
     */
    public function delete(string $path) {
        if ($this->filesystem->has($path)) {
            $this->filesystem->delete($path);
        }
    }

    /**
     * Information about if the filesystem contains a given file
     * 
     * @param string $path Path to the file
     * @return bool If the file exists
     */
    public function has(string $path): bool {
        return $this->filesystem->has($path);
    }
}