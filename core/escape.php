<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\escape: Escape/Unescape text
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

class escape {
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

        $core->registerComponent('escape', $this);
    }

    /**
     * Escape a string
     * 
     * This will:
     * - Escape HTML Entities using htmlspecialchars
     * - Convert line breaks to <br />
     * - Remove all remaining line breaks
     * 
     * @param string $string String to escape
     * @return string Escaped string
     */
    public function escape(string $string): string {
        $string = htmlspecialchars($string);
        $string = nl2br($string);
        $string = str_replace(["\r\n", "\r", "\n"], '', $string);

        return $string;
    }

    /**
     * Unescape a string
     * 
     * This will:
     * - Replace <br /> with line breaks
     * - Decode HTML entities using html_entity_decode
     * 
     * @param string $string String to unescape
     * @return string Unescaped string
     */
    public function unescape(string $string): string {
        $string = str_replace(['<br>', '<br/>', '<br />'], PHP_EOL, $string);
        $string = html_entity_decode($string);

        return $string;
    }
}