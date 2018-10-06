<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\update: Handle Journal updates
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

class update {
    /**
     * Instance of core\core that is connected to this instance
     * 
     * @var \core\core
     */
    public $core;

    /**
     * Cache for newest version availible
     * 
     * @var string
     */
    public $newestVersion;

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

        $core->registerComponent('update', $this);
    }

    /** 
     * Check if all needed folders for the update process have the right permissions
     * for Journal to read and write to it
     * 
     * @return array [
     *      'permissions' => array Folders and if they have the right permissions
     *      'hasErrors' => bool If there are permission errors at all
     * ]
     */
    public function checkPermissions() {
        $permissions = [
            [
                'folder' => 'assets/',
                'hasPermissions' => true
            ],
            [
                'folder' => 'core/',
                'hasPermissions' => true
            ],
            [
                'folder' => 'dashboard/',
                'hasPermissions' => true
            ],
            [
                'folder' => 'themes/',
                'hasPermissions' => true
            ],
            [
                'folder' => 'vendor/',
                'hasPermissions' => true
            ],
            [
                'folder' => 'extracted/',
                'hasPermissions' => true
            ]
        ];
        $hasErrors = false;

        if (!file_exists('extracted/')) {
            mkdir('extracted/');
        }

        foreach($permissions as $key => $folder) {
            if (!is_readable($folder['folder']) || !is_writable($folder['folder'])) {
                $permissions[$key]['hasPermissions'] = false;
                $hasErrors = true;
                continue;
            }

            $files = $this->core->component('file')->folderContent($folder['folder']);
            foreach($files as $file) {
                if (!is_readable($file) || !is_writable($file)) {
                    $permissions[$key]['hasPermissions'] = false;
                    $hasErrors = true;
                    break;
                }
            }
        }

        return [
            'permissions' => $permissions,
            'hasErrors' => $hasErrors
        ];
    }

    /**
     * Get the current Journal version
     * 
     * @return string Version of Journal
     */
    public function currentVersion(): string {
        $composer = file_get_contents('composer.json');
        $composer = json_decode($composer, true);
        return $composer['version'];
    }

    /**
     * Perform a cURL request
     * 
     * @param string $url URL to request
     * @return string URL contents
     */
    private function curlRequest(string $url): string {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Journal Updater'
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }

    /**
     * Newest Journal version availible
     * 
     * @return string Version
     */
    public function newestVersion(): string {
        if ($this->newestVersion) {
            return $this->newestVersion;
        }

        $resp = $this->curlRequest('https://api.github.com/repos/vantezzen/journal/releases');
        $data = json_decode($resp, true);
        if (isset($data[0]['name'])) {
            $version = $data[0]['name'];
        } else {
            $version = '1.0.0';
        }
        $this->newestVersion = $version;

        return $version;
    }

    /**
     * Wheather a new update exists
     * 
     * @return bool If an update exists
     */
    public function updateExists(): bool {
        return version_compare($this->currentVersion(), $this->newestVersion(), '<');
    }

    /**
     * Changelog for the newest version
     * 
     * @return string Changelog
     */
    public function changelog(): string {
        $resp = $this->curlRequest('https://api.github.com/repos/vantezzen/journal/releases');
        $data = json_decode($resp, true);
        $changelog = $data[0]['body'];

        return $changelog;
    }

    /**
     * Perform a Journal update to the newest Journal version
     * 
     * @return void
     */
    public function update() {
        // Get download URL
        $resp = $this->curlRequest('https://api.github.com/repos/vantezzen/journal/releases');
        $data = json_decode($resp, true);
        $download = $data[0]['zipball_url'];

        // Download update
        if (file_exists('./update.zip')) {
            unlink('./update.zip');
        }
        $curl = curl_init();
        $file = fopen('update.zip','w+');
        curl_setopt($curl, CURLOPT_URL, $download);
        curl_setopt($curl, CURLOPT_FILE, $file);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5040);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Journal Updater');
        curl_exec($curl);
        curl_close($curl);

        // Clear extracted/ folder
        $this->core->component('file')->deleteFolder('extracted/');

        // Extract zip to extracted/
        $zip = new \ZipArchive;
        $zip->open('update.zip');
        $zip->extractTo('./extracted/');
        $zip->close();

        // Find folder with update inside extracted/ folder
        $folder = false;
        foreach (scandir('extracted/') as $result) {
            if ($result === '.' or $result === '..') continue;

            if (is_dir('extracted/' . $result)) {
                $folder = $result;
                break;
            }
        }

        // Move updated files
        $files = $this->core->component('file')->folderContent('extracted/' . $folder, false);

        foreach($files as $file) {
            $final = str_replace('extracted/' . $folder . '/', './', $file);
            
            if (is_dir($file)) {
                if (!file_exists($final)) {
                    mkdir($final);
                }
                continue;
            }

            $content = file_get_contents($file);

            $f = fopen($final, 'w');
            fwrite($f, $content);
            fclose($f);
        }

        // Clear up
        unlink('update.zip');
        $this->core->component('file')->deleteFolder('extracted/');
    }
}