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

final class UrlTest extends TestCase {
    protected static $core;
    protected static $examplePost = [
        'id' => 1,
        'title' => 'My blog post'
    ];

    public static function setUpBeforeClass() {
        // Set up core
        self::$core = new core();
        self::$core->settings = [
            'url' => 'https://example.com',
            'url_format' => 1
        ];
        
        // Set up class
        new url(self::$core);
    }

    public function testGetFormatOne() {
        self::$core->settings['url_format'] = 1;
        $url = self::$core->component('url')->get(self::$examplePost);

        $this->assertEquals('1.html', $url);
    }

    public function testGetFullFormatOne() {
        self::$core->settings['url_format'] = 1;
        $url = self::$core->component('url')->getFull(self::$examplePost);

        $this->assertEquals('https://example.com/1.html', $url);
    }

    public function testGetFormatTwo() {
        self::$core->settings['url_format'] = 2;
        $url = self::$core->component('url')->get(self::$examplePost);

        $this->assertEquals('my-blog-post.html', $url);
    }

    public function testGetFullFormatTwo() {
        self::$core->settings['url_format'] = 2;
        $url = self::$core->component('url')->getFull(self::$examplePost);

        $this->assertEquals('https://example.com/my-blog-post.html', $url);
    }
}