<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * tests/core\comments: Test core\comments class
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
use core\comments;

// Dependencies of core\comments
use core\file;
use core\dashboard;
use core\url;

final class CommentsTest extends TestCase {
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
            'comments_identifier' => 'test',
            'comments_provider' => 0
        ];
        
        // Set up class
        new comments(self::$core);

        // Set up dependencies
        new file(self::$core);
        new dashboard(self::$core);
        new url(self::$core);
    }

    public function testEmptyGenerateCode() {
        self::$core->settings['comments_provider'] = 0;
        $comments = self::$core->component('comments')->generateCode(self::$examplePost);

        $this->assertEmpty($comments);
    }

    public function testDisqusGenerateCode() {
        $code = <<<EOF
<div id="disqus_thread" class="comments disqus"></div>
<script>

var disqus_config = function () {
this.page.url = 'https://example.com/my-blog-post.html';
this.page.identifier = '1';
};
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://test.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
EOF;
        self::$core->settings['comments_provider'] = 'disqus';
        $comments = self::$core->component('comments')->generateCode(self::$examplePost);

        $this->assertEquals($code, $comments);
    }

}