<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\database: Manage (connections to) the vowserDB database
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

use vowserDB\Table;

class database {
    /**
     * Instance of core\core that is connected to this instance
     * 
     * @var \core\core
     */
    public $core;

    /**
     * vowserDB instances for loaded tables
     * 
     * @var array
     */
    public $tables = [];

    /**
     * Constructor
     * 
     * The constructor will save the core instance, register itself on the core instance,
     * initialize the tables and load settings from the settings table.
     *
     * @param core $core Instance of the core to connect to
     * @return void
     */
    public function __construct(core $core) {
        $this->core = $core;
        $this->initTables();
        $this->loadSettings();

        $core->registerComponent('database', $this);
    }

    /**
     * Initiate needed tables
     * 
     * This method will create vowserDB\Table instances for
     *  - posts : Table for all posts
     *  - settings : Table that saves all settings
     * and saves them into the tables array
     * 
     * @return void
     */
    private function initTables(): void {
        $this->tables['posts'] = new Table('posts', [
            'id',
            'title',
            'text',
            'revision',
            'published',
            'created',
            'updated'
        ], false, ['folder' => 'tables/']);

        $this->tables['settings'] = new Table('settings', [
            'key',
            'value'
        ], false, ['folder' => 'tables/']);

        $this->tables['menu'] = new Table('menu', [
            'text',
            'url'
        ], false, ['folder' => 'tables/']);
    }

    /**
     * Load settings from the settings table and set them as the
     * $core's $settings array
     * 
     * @return void
     */
    public function loadSettings(): void {
        $data = $this->tables['settings']->data();
        $settings = [];

        // Convert settings from $settings[] = [key, value] to $settings[$key] = $value
        foreach($data as $setting) {
            $settings[$setting['key']] = $setting['value'];
        }

        // Set as core's settings
        $this->core->settings = $settings;
    }

    /**
     * Get a tables instance
     * 
     * @param string $name Name of the table
     * @return \vowserDB\Table Table instance
     */
    public function table(string $name): Table {
        return $this->tables[$name];
    }

    /**
     * Get all data from a table
     * 
     * @param string $table Table to get data from
     * @return array Data from the table
     */
    public function tableData(string $table): array {
        return $this->tables[$table]->data();
    }
}