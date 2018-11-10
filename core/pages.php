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
     * @param int $page Page to generate (if pagination is enabled)
     * @return string Page code
     */
    public function home(int $page = 0): string {
        // Get posts from database and reverse (newest posts on top)
        $posts = $this->core->component('database')->table('posts')->select(['published' => '1'])->selected();
        $posts = array_reverse($posts);

        $prevPageAvailible = false;
        $nextPageAvailible = false;

        // Apply pagination if enabled
        if ($this->core->setting('pagination') == 'yes') {
            $steps = $this->core->setting('pagination_steps');
            $start = $page * $steps;

            // Get information about previous and next page
            $prevPageAvailible = ($page !== 0);
            $nextPageAvailible = (count($posts) > ($start + $steps));

            $prevPage = ($page - 1) == 0 ? 'index.html' : 'home-' . ($page - 1) . '.html';
            $nextPage = 'home-' . ($page + 1) . '.html';

            // Get new posts array
            $posts = array_slice($posts, $start, $steps);
        }

        // Add additional information to posts
        foreach($posts as $key => $post) {
            if (strlen(trim($post['text'])) > 200) {
                $posts[$key]['trimmedText'] = substr($post['text'], 0, 197) . '...';
            } else {
                $posts[$key]['trimmedText'] = $post['text'];
            }
            $posts[$key]['path'] = $this->core->component('url')->get($post);
            $posts[$key]['url'] = $this->core->component('url')->getFull($post);
            $posts[$key]['text'] = $this->core->component('intelliformat')->format($post['text']);
        }

        // Generate page
        $render = $this->core->component('generator')->render('home', [
            'posts' => $posts,

            'pagination' => $this->core->setting('pagination') == 'yes',
            'prev' => $prevPageAvailible ? $prevPage : false,
            'next' => $nextPageAvailible ? $nextPage : false
        ]);

        return $render;
    }

    /**
     * Generate page for a post
     * 
     * @param array $post Post to generate page for
     * @return string Page code
     */
    public function post(array $post): string {
        $post['text'] = $this->core->component('intelliformat')->format($post['text']);

        // Collect data for render
        $data = array_merge($post, [
            'path' => $this->core->component('url')->get($post),
            'url' => $this->core->component('url')->getFull($post),
            'comments' => $this->core->component('comments')->generateCode($post)
        ]);

        // Generate page
        $render = $this->core->component('generator')->render('post', $data);

        return $render;
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