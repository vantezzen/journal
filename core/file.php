<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\file: Manage file access
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

class file {
    /**
     * Instance of core\core that is connected to this instance
     * 
     * @var \core\core
     */
    public $core;
    
    /**
     * Loaded files
     * 
     * Read files will be loaded into this array for faster access when the file is next needed
     * 
     * @var array
     */
    public $files = [];

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

        $core->registerComponent('file', $this);
    }

    //////// READ FILE ////////
    /**
     * Load an html file from themes/[theme] into the files array
     * 
     * @param string $file File to load
     * @return void
     */
    public function loadFile(string $file) {
        $this->files[$file] = file_get_contents($file);
    }

    /**
     * Get an html file from themes/[theme] using the $files array
     * 
     * @param $file File to get
     * @return string File content
     */
    public function getFile(string $file): string {
        if (!isset($this->files[$file])) {
            $this->loadFile($file);
        }
        return $this->files[$file];
    }


    //////// WRITE ////////
    /**
     * Save text to a file in the public/ folder, erasing all previous data in the file
     * 
     * @param string $file File to insert into
     * @param string $content Content to insert into file
     * @return void
     */
    public function save($file, $content) {
        $file = fopen($file, 'w');
        fwrite($file, $content);
        fclose($file);
    }

    //////// COPY ////////
    /**
     * Recursively copy files and folders in folder
     * 
     * This method will be used to copy the assets folder from themes/
     * to public/
     * 
     * @param string $src Source folder to copy
     * @param string $dst Destination folder to copy folder to
     * @return void
     * @source https://stackoverflow.com/a/2050909/10005649
     */
    public function copyFolder(string $src, string $dst): void { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->copyFolder($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 

    //////// DELETE ////////
    /**
     * Recursively delete a directorys content
     * 
     * This method is used before generating static blog files
     * to delete all previous files
     * 
     * @param string $str Folder to delete content of
     * @param bool $deleteFolders Delete folders or keep
     * @return void
     * @source https://stackoverflow.com/a/4594277/10005649
     */
    public function deleteFolder(string $str, bool $deleteFolders = true) {
        if (is_file($str)) {
            unlink($str);
        } else if (is_dir($str)) {
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index => $path) {
                $this->deleteFolder($path);
            }
            if ($deleteFolders === true) {
                rmdir($str);
            }
        }
    }

    //////// INFO ////////
    /**
     * Get files and folders in a given folder - including all subfolders
     * 
     * @param string $dir Folder to get contents of
     * @param bool $realPath Wheather to return the absolute path of the files
     * @param array $results Results. This is used when the function is calling itself recursively
     * @return array Contents of folder
     * 
     * @source https://stackoverflow.com/a/24784144/10005649
     */
    public function folderContent(string $dir, bool $realPath = true, array &$results = array()){
        // Get contents of current directory
        $files = scandir($dir);
    
        foreach($files as $key => $value){
            $path = $dir.DIRECTORY_SEPARATOR.$value;
            if ($realPath) {
                $path = realpath($path);
            }
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                $this->folderContent($path, $realPath, $results);
                $results[] = $path;
            }
        }
    
        return $results;
    }
}