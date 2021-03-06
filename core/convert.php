<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\convert: Manage the conversion into static files
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

class convert {
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

        $core->registerComponent('convert', $this);
    }

    /**
     * Clear files in public/ folder
     * 
     * @return void
     */
    public function clearFolder() {
        $this->core->component('file')->deleteFolder('public/', false);
    }

    /**
     * Copy the assets folder from themes/[theme]/assets to public/assets
     * 
     * @return void
     */
    public function copyAssets() {
        $this->core->component('file')->copyFolder('themes/' . $this->core->setting('theme') . '/assets/', 'public/assets/');
    }

    /**
     * Convert whole website to static files in public folder
     * 
     * @return void
     */
    public function all() {
        // Clear folder before generating
        $this->clearFolder();

        // Copy assets folder
        $this->copyAssets();

        // Intialize variables
        $files = [];
        $posts = $this->core->component('database')->table('posts')->select(['published' => '1'])->selected();

        // Generate Homepage
        $index = $this->core->component('pages')->home();
        $this->core->component('file')->save('public/index.html', $index);
        $files[] = 'index.html';

        // Apply pagination if enabled
        if ($this->core->setting('pagination') == 'yes') {
            $steps = $this->core->setting('pagination_steps');
            if (!is_numeric($steps)) {
                $steps = 20;
            }

            $num = count($posts);
        
            // Create new pages while there are still posts left
            for ($i = 1; ($i * $steps) < $num; $i++) {
                $page = $this->core->component('pages')->home($i);
                $this->core->component('file')->save('public/home-' . $i . '.html', $page);
                $files[] = 'home-' . $i . '.html';

                // Stop if $i bigger 200 - there is probably an error
                if ($i > 200) {
                    echo "Oh! We couldn't generate your blog as there was an error while generating:<br />";
                    echo "There were over 200 home pages to generate - this is probably an error.<br />";
                    echo "Journal has stopped generating new files to save your webserver from crashing.<br />";
                    echo "Debugging Info: <br />";
                    var_dump($i, $steps, $num, is_numeric($steps), $this->core->version);
                    exit();
                }
            }
        }

        // Generate Posts
        foreach($posts as $post) {
            $page = $this->core->component('pages')->post($post);
            $path = $this->core->component('url')->get($post);

            $this->core->component('file')->save('public/' . $path, $page);
            $files[] = $path;
        }

        // Generate RSS Feed
        $index = $this->core->component('pages')->feed();
        $this->core->component('file')->save('public/feed.xml', $index);
        $files[] = 'feed.xml';

        // Generate info.json
        $info = [
            'generator' => 'Journal',
            'last_update' => time(),
            'files' => $files
        ];
        $this->core->component('file')->save('public/info.json', json_encode($info));
    }
}