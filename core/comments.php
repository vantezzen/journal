<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\comments: Generate code for comment section
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

class comments {
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

        $core->registerComponent('comments', $this);
    }

    /**
     * Generate the comment section code for a post
     * 
     * @param array $post Post to generate a comment section for
     * @return string Comment section code
     */
    public function generateCode(array $post): string {
        $code = '';

        if($this->core->setting('comments_provider') === 'disqus') {
            $template = $this->core->component('dashboard')->getFile('comments/disqus');
            $code = $this->core->component('dashboard')->render($template, [
                'url' => $this->core->setting('url'),
                'full_url' => $this->core->component('url')->getFull($post),
                'identifier' => $post['id'],
                'prefix' => $this->core->setting('comments_identifier')
            ]);
        }

        return $code;
    }
}