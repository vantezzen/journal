<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\dashboard: Generate pages for the dashboard
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

use Mustache_Engine;

class dashboard {
    /**
     * Instance of core\core that is connected to this instance
     * 
     * @var \core\core
     */
    public $core;

    /**
     * Mustache_Engine instance used to render pages
     * 
     * @var \Mustache_Engine
     */
    public $mustache;
    
    /**
     * Constructor
     * 
     * The constructor will save the core instance, register itself on the core instance
     * and initiate a new Mustache_Engine instance.
     *
     * @param core $core Instance of the core to connect to
     * @return void
     */
    public function __construct(core $core) {
        $this->core = $core;
        $this->mustache = new Mustache_Engine;

        $core->registerComponent('dashboard', $this);
    }

    /**
     * Get an html file from dashboard/template/ using core\files
     * 
     * @param $file File to get
     * @return string File content
     */
    public function getFile(string $file): string {
        return $this->core->component('file')->getFile('dashboard/template/' . $file . '.html');
    }

    /**
     * Render using Mustache_Engine instance
     * 
     * @param string $template Template to render
     * @param array $content Content to insert into page
     * @return string Rendered page
     */
    public function render(string $template, array $content): string {
        return $this->mustache->render($template, $content);
    }

    /**
     * Apply the base template to a page
     * 
     * @param string $content Content page to insert into base template
     * @param string $url URL to use in base template
     * @return string Rendered base template with supplied content
     */
    public function applyBase(string $content, string $url = './'): string {
        $template = $this->getFile('base');
        $render = $this->renderBaseTemplate($template, $content, $url);

        return $render;
    }

    /**
     * Render a base template file
     * 
     * This is used recursively inside applyBase() to give all base template files and includes
     * access to the same variables and functions.
     * 
     * @param string $template Template to use when rendering
     * @param mixed $content Content to supply to the render function
     * @return string Rendered page
     */
    public function renderBaseTemplate(string $template, $content, string $url): string {
        if ($this->core->setting('search_updates') === 'yes') {
            $update = $this->core->component('update')->updateExists();
        } else {
            $update = false;
        }

        $render = $this->render($template, array(
            'content' => $content,
            'url' => $url,
            'update' => $update,
            'include' => function($file) use ($content, $url) {
                $template = $this->getFile(trim($file));
                $render = $this->renderBaseTemplate($template, $content, $url);
                return $render;
            }
        ));

        return $render;
    }

    /**
     * Render the index page of the dashboard
     * 
     * Used when calling GET /
     * 
     * @param string $url URL to use inside the template
     * @return string Rendered page
     */
    public function renderIndex(string $url): string {
        // Get template content
        $template = $this->getFile('index');

        // Get posts and reverse array (new posts on top)
        $posts = $this->core->component('database')->tableData('posts');
        $posts = array_reverse($posts);

        foreach($posts as $key => $post) {
            // Apply IntelliFormat formatting
            $posts[$key]['text'] = $this->core->component('intelliformat')->format($post['text']);
        }

        // Check if is uploading in background or is uploadable
        $settings = $this->core->component('database')->table('settings');
        $uploading = (count($settings->select(['key' => 'uploading'])->selected()) > 0 && $settings->selected()[0]['value'] > (time() - 3600));
        $uploadable = ($this->core->setting('upload_uploader') !== 0 && !$uploading);

        // 'Uploading' in database but over 1h old => Delete
        if (count($settings->select(['key' => 'uploading'])->selected()) > 0 
            && $settings->selected()[0]['value'] < (time() - 3600)) {
                $settings->select(['key' => 'uploading'])->delete()->save();
        }

        // Render page using posts
        $render = $this->render($template, [
            'post' => $posts,
            'url' => $url,
            'uploadable' => $uploadable,
            'uploading' => $uploading
        ]);

        // Apply base template to page
        $page = $this->applyBase($render, $url);

        return $page;
    }

    /**
     * Render 404 page
     * 
     * Used when calling an unexistent page
     * 
     * @param string $url URL to use while rendering
     * @return string Rendered page
     */
    public function renderNotFound(string $url): string {
        $template = $this->getFile('404');
        
        // Render page
        $render = $this->render($template, []);
        $page = $this->applyBase($render, $url);

        return $page;
    }

    /**
     * Render the write page
     * 
     * Used when calling GET /write(/[id])
     * 
     * @param mixed $post Post to edit or false if new post
     * @param string $url URL to use while rendering
     * @return string Rendered page
     */
    public function renderWrite($post = false, string $url = './'): string {
        // Get write template
        $template = $this->getFile('write');

        $isEdit = true;
        if ($post === false) {
            // Empty data if new post
            $post = [
                'id' => false,
                'title' => '',
                'text' => ''
            ];
            $isEdit = false;
        }

        // Create data array for rendering
        $data = [
            'title' => $this->core->component('escape')->unescape($post['title']),
            'text' => $this->core->component('escape')->unescape($post['text']),
            'id' => $post['id'],
            'edit' => $isEdit,
            'url' => $url
        ];

        // Render page
        $render = $this->render($template, $data);

        // Apply base template to rendered page
        $page = $this->applyBase($render, $url);

        return $page;
    }

    /**
     * Render uploading page
     * 
     * Used when calling /uploading
     * 
     * @param string $url URL to use while rendering
     * @return string Rendered page
     */
    public function renderUploading(string $url): string {
        $template = $this->getFile('uploading');
        $render = $this->render($template, ['url' => $url]);
        $page = $this->applyBase($render, $url);

        return $page;
    }

    /**
     * Render settings page
     * 
     * Used when calling GET /settings
     * 
     * @param string $url URL to use while rendering
     * @return string Rendered page
     */
    public function renderSettings(string $url): string {
        $template = $this->getFile('settings');
        // Render using current settings
        $data = $this->core->settings;

        $data['page_url'] = $url;
        // Get availible themes
        $folders = scandir('themes/');
        $themes = [];

        foreach ($folders as $key => $theme) {
            if (($theme !== '.') && ($theme !== '..') && is_dir('themes/' . $theme)) {
                $themes[] = $theme;
            }
        }
        $data['availible_themes'] = $themes;

        if ($this->core->setting('search_updates') === 'yes') {
            $data['update'] = $this->core->component('update')->updateExists();
        } else {
            $data['update'] = false;
        }

        // Check if is uploading in background or is uploadable
        $settings = $this->core->component('database')->table('settings');
        $data['uploading'] = (count($settings->select(['key' => 'uploading'])->selected()) > 0 && $settings->selected()[0]['value'] > (time() - 3600));
        $data['uploadable'] = ($this->core->setting('upload_uploader') !== 0 && !$data['uploading']);
        
        $render = $this->render($template, $data);
        $page = $this->applyBase($render, $url);

        return $page;
    }

    /**
     * Render menu page
     * 
     * Used when calling GET /menu
     * 
     * @param string $url URL to use while rendering
     * @return string Rendered page
     */
    public function renderMenu(string $url): string {
        $template = $this->getFile('menu');

        $items = $this->core->component('database')->tableData('menu');
        $data = [
            'items' => $items,
            'url' => $url
        ];

        $render = $this->render($template, $data);
        $page = $this->applyBase($render, $url);

        return $page;
    }

    /**
     * Render installation page
     * 
     * Used when calling GET /install
     * 
     * @param string $url URL to use while rendering
     * @return string Rendered page
     */
    public function renderInstall(string $url): string {
        $template = $this->getFile('install');
        // Render using current settings
        $data = $this->core->settings;

        $data['page_url'] = $url;

        // Get availible themes
        $folders = scandir('themes/');
        $themes = [];

        foreach ($folders as $key => $theme) {
            if (($theme !== '.') && ($theme !== '..') && is_dir('themes/' . $theme)) {
                $themes[] = $theme;
            }
        }
        $data['availible_themes'] = $themes;

        // Create tables and public folder if not existent
        if (!file_exists('tables/')) {
            mkdir('tables');
        }
        if (!file_exists('public/')) {
            mkdir('public');
        }

        // Check if Journal can read and write to public and tables
        $data['read_tables'] = (is_readable('tables/') && is_writable('tables/'));
        $data['read_public'] = (is_readable('public/') && is_writable('public/'));
        $data['has_errors'] = !($data['read_tables'] && $data['read_public']);

        // Render installation page withour wrapping in base.html
        $render = $this->render($template, $data);

        return $render;
    }

    /**
     * Render update page
     * 
     * Used when calling GET /update
     * 
     * @param string $url URL to use while rendering
     * @return string Rendered page
     */
    public function renderUpdate(string $url): string {
        $template = $this->getFile('update');
        
        $permissions = $this->core->component('update')->checkPermissions();

        $changelog = $this->core->component('update')->changelog();
        $parsedown = new \Parsedown();
        $parsedown->setSafeMode(true);
        $changelog = $parsedown->text($changelog);

        // Information needed for the update screen
        $data = [
            'folder' => $permissions['permissions'],
            'has_errors' => $permissions['hasErrors'],
            'current_version' => $this->core->component('update')->currentVersion(),
            'newest_version' => $this->core->component('update')->newestVersion(),
            'changelog' => $changelog,
            'page_1' => true,
            'url' => $url
        ];
        $data['update_availible'] = version_compare($data['current_version'], $data['newest_version'], '<');

        // Render page
        $render = $this->render($template, $data);
        $page = $this->applyBase($render, $url);

        return $page;
    }

    /**
     * Render update done page
     * 
     * Used when calling POST /update
     * 
     * @param string $url URL to use while rendering
     * @return string Rendered page
     */
    public function renderUpdateDone(string $url): string {
        // Render page
        $template = $this->getFile('update_done');
        $render = $this->render($template, ['url' => $url]);
        $page = $this->applyBase($render, $url);

        return $page;
    }

}