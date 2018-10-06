<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * core\intelliformat: Automatically Format 
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

use \Misd\Linkify\Linkify;
use \Parsedown;

class intelliformat {
    /**
     * Instance of core\core that is connected to this instance
     * 
     * @var \core\core
     */
    public $core;

    /**
     * Linkify instance used to format posts
     * 
     * @var \Misd\Linkify\Linkify
     */
    public $linkify;

    /**
     * Parsedown instance used to format posts
     * 
     * @var \Parsedown
     */
    public $parsedown;
    
    /**
     * Constructor
     * 
     * The constructor will save the core instance, register itself on the core instance
     * and create a new Linkify instance.
     *
     * @param core $core Instance of the core to connect to
     * @return void
     */
    public function __construct(core $core) {
        $this->core = $core;
        $this->linkify = new Linkify();
        $this->parsedown = new Parsedown();

        $core->registerComponent('intelliformat', $this);
    }

    /**
     * Apply intelligent formatting.
     * 
     * This will:
     *  - Format Code
     *  - Convert links
     *  - Create headings
     *  - Convert Markdown
     * 
     * @param string $string String to format
     * @return string Formatted string 
     */
    public function format(string $string): string {
        if ($this->core->setting('intelliformat_code') !== 'no') {
            $string = $this->formatCode($string);
        }
        if ($this->core->setting('intelliformat_links') !== 'no') {
            $string = $this->formatLinks($string);
        }
        if ($this->core->setting('intelliformat_headings') !== 'no') {
            $string = $this->formatHeadings($string);
        }
        if ($this->core->setting('intelliformat_markdown') !== 'no') {
            $string = $this->formatMarkdown($string);
        }
        return $string;
    }

    /**
     * Convert URL to HTML Links
     * 
     * @param string $string String to format
     * @return string Formatted string
     */
    public function formatLinks(string $string): string {
        return $this->linkify->process($string);
    }

    /**
     * Format Markdown
     * 
     * @param string $string String to format
     * @return string Formatted string
     */
    public function formatMarkdown(string $string): string {
        return $this->parsedown->line($string);
    }

    /**
     * Add h3 tags to headings
     * 
     * @param string $string String to format
     * @return string Formatted string
     */
    public function formatHeadings(string $string): string {
        $lines = explode('<br />', $string);

        $lastEmpty = false;
        foreach($lines as $num => $line) {
            if (empty(trim($line))) {
                $lastEmpty = true;
                continue;
            }
            $firstChar = substr(trim($line), 0, 1);
            if (
                $lastEmpty &&           // Last line has to be empty
                strlen($line) <= 40 &&  // Can not be longer than 40
                $firstChar !== '-' &&// Can not be a list (starting with -)
                $firstChar !== '#'  // Not a markdown formatted heading
            ) {
                // Check if code formatting is on and line is code
                if ($this->core->setting('intelliformat_code') == 'no' || !($this->isCode($line))) {
                    $lines[$num] = '<h5><b>' . $line . '</b></h5>';
                }
            }
            $lastEmpty = false;
        }
        $string = implode("<br />", $lines);
        return $string;
    }

    /**
     * Format code into <pre> tags
     * 
     * @param string $string String to format
     * @return string Formatted string
     */
    public function formatCode(string $string): string {
        $lines = explode('<br />', $string);

        $codeBlock = false; // Is currently a code block
        $final = ''; // Final string
        foreach($lines as $num => $line) {
            if (empty(trim($line))) {
                $final .= '<br />';
                continue;
            }

            if ($this->isCode($line) && !$codeBlock) {
                // Start a code block
                $codeBlock = true;
                $final .= '<pre>';
            } else if (!$this->isCode($line) && $codeBlock) {
                // End code block
                $codeBlock = false;
                $final .= '</pre>';
            }

            $final .= $line . '<br />';
        }

        // End code block if still active
        if ($codeBlock) {
            $final .= '</pre>';
        }

        return $final;
    }

    /**
     * Detect if a text line is code
     * 
     * @param string $string String to format
     * @return string Formatted string
     */
    public function isCode(string $string): bool {
        // Ends in semicolon: Probably code (but not an HTML encoded character)
        if (substr(trim($string), -1) == ';' && !preg_match('/&\w{1,8};$/', trim($string))) {
            return true;
        }

        // Matches typical code:
        // function_name(arguments)
        // if (preg_match('/^\w+( )?\(.*\)/', trim($string))) {
        //     return true;
        // }

        // End with { (for if)
        if (substr(trim($string), -1) == '{') {
            return true;
        }

        // End with } (for end of if)
        if (substr(trim($string), -1) == '}') {
            return true;
        }

        // End with }, (for end of if)
        if (substr(trim($string), -2) == '},') {
            return true;
        }

        // Matches HTML:
        // <something> or </something>
        if (preg_match('/&lt;\/?\w+.*&gt;/', $string)) {
            return true;
        }

        // Is a comment
        // // ...
        if (preg_match('/^(\/\/|#).*$/', trim($string))) {
            return true;
        }

        // End with a semicolon, then a comment
        // ...; // I am a comment
        if (preg_match('/;.?(\/\/|#).*$/', trim($string))) {
            return true;
        }

        // Has double- or triple-equals
        if (preg_match('/={2,3}/', $string)) {
            return true;
        }

        return false;
    }
}