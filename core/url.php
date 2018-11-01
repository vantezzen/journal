<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\url: Generate URLs/Paths for posts
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

use Cocur\Slugify\Slugify;

class url {
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

        $core->registerComponent('url', $this);
    }

    /**
     * Get the relative url/path for post
     * 
     * @param array $post Post to generate URL for
     * @return string URL
     * @example my-blog-post.html
     */
    public function get(array $post): string {
        switch($this->core->setting('url_format')) {
            case 2:
                $slugify = new Slugify();
                $url = $slugify->slugify($post['title']) . '.html';
                break;
            case 1:
            default:
                $url = $post['id'] . '.html';
                break;
        }
        return $url;
    }

    /**
     * Get full URL for post
     * 
     * @param array $post Post to generate URL for
     * @return string URL
     * @example https://example.com/my-blog-post.html
     */
    public function getFull(array $post): string {
        $base = $this->core->setting('url');
        $path = $this->get($post);

        return $base . '/' . $path;
    }
}