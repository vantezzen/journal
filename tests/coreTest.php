<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * tests/core\url: Test core\url class
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
use core\url;

final class CoreTest extends TestCase {
    protected static $core;
    public static $test = 'UNIT TEST';

    public static function setUpBeforeClass() {
        // Set up core
        self::$core = new core();
    }

    public function testRegisterComponent() {
        self::$core->registerComponent('test', $this);

        $this->assertEquals([
            'test' => $this
        ], self::$core->components);
    }

    public function testGetComponent() {
        self::$core->registerComponent('test', $this);

        // Get component through function
        $this->assertEquals($this, self::$core->component('test'));

        // Get component through __get
        $this->assertEquals($this, self::$core->test);
    }

    public function testGetSetting() {
        self::$core->settings = [
            'unit' => 'test',
            'test' => 'unit'
        ];

        // Get set setting
        $this->assertEquals('test', self::$core->setting('unit'));

        // Get unset setting => Fallback
        $this->assertEquals('default', self::$core->setting('theme'));

        // Get unexisting setting => False
        $this->assertEquals(false, self::$core->setting('invalid'));
    }

}