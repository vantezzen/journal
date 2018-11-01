<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\uploaders\UploaderInterface: Interface for Journal uploaders
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

interface UploaderInterface {
    /**
     * Constructor that will save the connected core instance
     * 
     * @param core $core Connected core
     * @return void
     */
    public function __construct(core $core);

    /**
     * Set up the filesystem and set $this->filesystem to it
     * 
     * @return void
     */
    public function setup();

    /**
     * Upload a file to the server using given file content and path.
     * 
     * This method should also able to overwrite the file if it already exists, e.g. by deleting beforehand
     * 
     * @param string $content Content of the file being uploaded
     * @param string $path Relative path to the file. This path should be added to the upload_path setting to get the absolute path
     * @return void
     */
    public function upload(string $content, string $path);

    /**
     * Delete a file with a given absolute path from the server
     * 
     * This method should be able to handle if the given file does not exist
     * 
     * @param string $path Absolute path to the file
     * @return void
     */
    public function delete(string $path);
}