<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\pages: Instruction on how to generate different static pages
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

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

class pages {
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

        $core->registerComponent('pages', $this);
    }

    /**
     * Generate the static homepage
     * 
     * @return string Page code
     */
    public function home(): string {
        // Get posts from database and reverse (newest posts on top)
        $posts = $this->core->component('database')->tableData('posts');
        $posts = array_reverse($posts);

        // Add additional information to posts
        foreach($posts as $key => $post) {
            $posts[$key]['path'] = $this->core->component('url')->get($post);
            $posts[$key]['url'] = $this->core->component('url')->getFull($post);
            $posts[$key]['trimmedText'] = function($length) use ($post) {
                $length = trim($length);
                if (strlen($post['text']) > $length) {
                    return substr($post['text'], 0, $length - 3) . '...';
                } else {
                    return $post['text'];
                }
            };
        }

        // Generate page
        $template = $this->core->component('generator')->getFile('home');
        $render = $this->core->component('generator')->render($template, [
            'post' => $posts,
            'title' => $this->core->setting('title'),
            'description' => $this->core->setting('description'),
            'copyright' => $this->core->setting('copyright')
        ]);
        $page = $this->core->component('generator')->applyBase($render);

        return $page;
    }

    /**
     * Generate page for a post
     * 
     * @param array $post Post to generate page for
     * @return string Page code
     */
    public function post(array $post): string {
        $template = $this->core->component('generator')->getFile('post');

        // Collect data for render
        $data = array_merge($post, [
            'path' => $this->core->component('url')->get($post),
            'url' => $this->core->component('url')->getFull($post),
            'comments' => $this->core->component('comments')->generateCode($post)
        ]);

        // Generate page
        $render = $this->core->component('generator')->render($template, $data);
        $page = $this->core->component('generator')->applyBase($render);

        return $page;
    }

    /**
     * Generate RSS Feed
     * 
     * @return string RSS Feed code
     */
    public function feed(): string {
        // Create new Feed
        $feed = new Feed();

        // Create Channel for blog
        $channel = new Channel();
        $channel
            ->title($this->core->setting('title'))
            ->description($this->core->setting('description'))
            ->url($this->core->setting('url'))
            ->feedUrl($this->core->setting('url') . '/feed')
            ->language($this->core->setting('language'))
            ->copyright($this->core->setting('copyright'))
            // ->pubDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
            ->lastBuildDate(time())
            ->ttl(60)
            ->appendTo($feed);
        
        // Create items for blog posts
        $posts = $this->core->component('database')->tableData('posts');
        foreach($posts as $post) {
            $item = new Item();
            $item
                ->title($post['title'])
                ->description($post['text'])
                ->contentEncoded($post['text'])
                ->url($this->core->setting('url') . '/abc')
                ->author($this->core->setting('title'))
                // ->creator('John Smith')
                ->pubDate((int) $post['created'])
                ->guid($this->core->setting('url') . '/abc', true)
                ->preferCdata(true)
                ->appendTo($channel);
        }

        return $feed;
    }
}