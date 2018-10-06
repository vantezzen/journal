<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * tests/core\escape: Test core\escape class
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use core\core;
use core\escape;

final class EscapeTest extends TestCase {
    protected static $core;
    protected static $unescaped = <<<EOL
This > is a  Test &&&&

This is a test © ¢ <script></script>
EOL;
    protected static $escaped = "This &gt; is a  Test &amp;&amp;&amp;&amp;<br /><br />This is a test &copy; &cent; &lt;script&gt;&lt;/script&gt;";

    public static function setUpBeforeClass() {
        // Set up core
        self::$core = new core();
        
        // Set up class
        new escape(self::$core);
    }

    public function testEscape() {
        $input = self::$unescaped;
        $output = self::$escaped;
        $this->assertEquals($output, self::$core->component('escape')->escape($input));
    }
    public function testUnescape() {
        $input = self::$escaped;
        $output = self::$unescaped;
        $this->assertEquals($output, self::$core->component('escape')->unescape($input));
    }
}