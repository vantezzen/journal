<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\core: Main core for Journal, managing all other components,
 * enabling communications between components and saving settings
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

class core {
    /**
     * User settings
     * 
     * These settings will be filled from core\database once all tables have
     * been loaded
     * 
     * @var array
     */
    public $settings;

    /**
     * User settings fallback
     * 
     * This fallback will be used when a setting is not availible in the user
     * settings array
     * 
     * @var array
     */
    public $settingsFallback = [
        'theme' => 'default',
        'title' => 'Journal',
        'description' => '',
        'url' => 'https://example.com',
        'language' => 'EN',
        'copyright' => '',
        'url_format' => 2,
        'pagination' => 'no',
        'pagination_steps' => 20,
        'comments_provider' => 0,
        'upload_uploader' => 0,
        'upload_port' => '',
        'au_enabled' => true,
        'search_updates' => 'yes',
        'intelliformat_markdown' => 'no',
        'intelliformat_code' => 'no'
    ];

    /**
     * Loaded components
     * 
     * This array will save all currently loaded components, including their name
     * and instance variable
     * 
     * @var array
     */
    public $components = [];

    /**
     * Register a new component
     * 
     * This method will be executed inside the __construct method of components
     * to register itself to the core. The core then saves the component to the 
     * $components array to enable communication with the component
     * 
     * @param string $name Name of the component
     * @param $component Instance of the component
     * @return void
     */
    public function registerComponent(string $name, $component): void {
        $this->components[$name] = $component;
    }

    /**
     * Get a component
     * 
     * This function returns a loaded component to enable communication
     * to that component
     * 
     * @param string $name Name of the component to return
     * @return instance Loaded instance of the component
     */
    public function component(string $name) {
        return $this->components[$name];
    }

    /**
     * Get a component
     * 
     * This magic method allows the sytax $core->component to
     * return a loaded component.
     * 
     * @param string $name Name of the component to return
     * @return instance Loaded instance of the component
     */
    public function __get($name) {
        return $this->component($name);
    }

    /**
     * Get the value of a setting
     * 
     * This function will return the value or fallback value
     * of a setting
     * 
     * @param string $name Name of the setting
     * @return mixed Value, fallback value of false if the setting couldn't be found in eather
     */
    public function setting(string $name) {
        if (isset($this->settings[$name])) {
            return $this->settings[$name];
        } else if (isset($this->settingsFallback[$name])) {
            return $this->settingsFallback[$name];
        }
        return false;
    }
}