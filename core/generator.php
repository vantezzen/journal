<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\generator: Generate static page code
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

class generator {
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

        $core->registerComponent('generator', $this);
    }

    /**
     * Get an html file from themes/[theme] using core\files
     * 
     * @param $file File to get
     * @return string File content
     */
    public function getFile(string $file): string {
        return $this->core->component('file')->getFile('themes/' . $this->core->setting('theme') . '/' . $file . '.html');
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
    public function applyBase(string $content): string {
        $template = $this->getFile('base');
        $render = $this->renderBaseTemplate($template, $content);

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
    public function renderBaseTemplate(string $template, $content): string {
        $render = $this->render($template, array(
            'title' => $this->core->setting('title'),
            'description' => $this->core->setting('description'),
            'copyright' => $this->core->setting('copyright'),
            'language' => $this->core->setting('language'),
            'url' => $this->core->setting('url'),
            'content' => $content,
            'include' => function($file) use ($content) {
                $template = $this->getFile(trim($file));
                $render = $this->renderBaseTemplate($template, $content);
                return $render;
            }
        ));

        return $render;
    }
}