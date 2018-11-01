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

use Jenssegers\Blade\Blade;

class generator {
    /**
     * Instance of core\core that is connected to this instance
     * 
     * @var \core\core
     */
    public $core;

    /**
     * \Jenssegers\Blade\Blade instance used to render pages
     * 
     * @var \Jenssegers\Blade\Blade
     */
    public $blade;

    /**
     * Constructor
     * 
     * The constructor will save the core instance, register itself on the core instance
     * and initiate a new Blade instance.
     *
     * @param core $core Instance of the core to connect to
     * @return void
     */
    public function __construct(core $core) {
        $this->core = $core;
        $this->blade = new Blade('themes/' . $this->core->setting('theme') . '/', 'core/cache/');

        $core->registerComponent('generator', $this);
    }

    /**
     * Render using Mustache_Engine instance
     * 
     * @param string $template Template to render
     * @param array $content Content to insert into page
     * @return string Rendered page
     */
    public function render(string $template, array $content): string {
        $extend = $this->core->settings;
        $extend['menu'] = $this->core->component('database')->tableData('menu');

        $content = array_merge($content, $extend);

        return $this->blade->make($template, $content);
    }
}