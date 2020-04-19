<?php

/**
 * Head Helper
 * Register <head> tags from helpers, then print them
 * in head through layout.
 *
 * Requires UtilHelper >=0.20
 *
 * @author RosSoft
 * @license MIT
 * @version 0.5
 * @package helpers
 */
class HeadHelper extends Helper
{
    var $helpers = array('Html', 'Javascript', 'Util');

    var $_library; //static array of items to be included

    /**
     * Use cache for writing only one block tag.
     * Be aware that the order of print will be different
     * from register order, but the order within the block
     * isn't modified.
     * Similar to @see Javascript::cacheEvents
     */
    var $cache = true;


    function __construct()
    {
        static $library = array();  //for php4 compat
        $this->_library =& $library;
    }


    /**
     * Adds a css file to array
     * @param string $file CSS file to be included
     * @param string $param Array of htmlAttributes
     */
    function css($file, $htmlAttributes = null)
    {
        $this->_register(array($file, 'css', $htmlAttributes));
    }

    /**
     * Adds an inline css block to array
     * @param string $css CSS tags to be included
     * @param string $param Array of htmlAttributes
     */
    function cssblock($css, $htmlAttributes = null)
    {
        $this->_register(array($css, 'cssblock', $htmlAttributes));
    }


    /**
     * Adds a js file to array
     * @param string $file Javascript file to be included (in webroot/js or external if begins with http://)
     * @param string $param Array of htmlAttributes
     */
    function js($file)
    {
        if (preg_match('/^https?:\/\//', $file)) {
            $this->_register(array($file, 'jsexternal'));
        } else {
            $this->_register(array($file, 'js'));
        }
    }

    /**
     * Adds a javascript block to array
     * @param string $javascript Javascript block to be included
     * @param string $param Array of htmlAttributes
     */
    function jsblock($javascript)
    {
        $this->_register(array($javascript, 'jsblock'));
    }

    /**
     * Attach an event to an object using CSS syntax
     * The event will be attached on page load
     *
     * @param string $css CSS selector like "#mydiv"
     * @param string $event Event name like "click,load..."
     * @param string $jscode Javascript code attached
     */
    function event($css, $event, $jscode)
    {
        $js = "$$('$css').each(function(_elem){ Event.observe(_elem, \"$event\", function(event){ $jscode }, false);});";
        $this->onload($js);
    }

    /**
     * Register Javascript code to be executed on page load
     * @param string $jscode Javascript code
     */
    function onload($jscode)
    {
        $js = "Event.observe(window,'load',function() { $jscode});";
        $this->jsblock($js);
    }

    /**
     * Adds a meta tag to array
     * @param array $htmlAttributes Array of html attributes of meta tag
     */
    function meta($htmlAttributes)
    {
        $this->_register(array($htmlAttributes, 'meta'));
    }


    /**
     * Adds a meta refresh tag to array
     *
     * @param string $url Absolute URL or CakeURL
     * @param integer $seconds Seconds to wait before redirection
     * @param boolean $is_cake_url True if the $url parameter is a CakeURL
     */
    function refresh($url, $seconds = 10, $is_cake_url = true)
    {
        if ($is_cake_url) {
            $url = $this->Util->to_absolute_url($url);
        }
        $htmlAttributes = array('http-equiv' => 'refresh',
            'content' => "$seconds; $url");
        $this->_register(array($htmlAttributes, 'meta'));
    }

    /**
     * Adds a link tag to array
     * @param array $htmlAttributes Array of html attributes of meta tag
     */
    function link($htmlAttributes)
    {
        $this->_register(array($htmlAttributes, 'link'));
    }

    /**
     * Adds a raw sequence of html tags to array
     * @param string $raw Sequence of html tags
     */
    function raw($raw)
    {
        $this->_register(array($raw, 'raw'));
    }

    /**
     * Prints the html for all of the items registered
     * @return string
     */
    function registered()
    {
        if ($this->cache) {
            $cached = array('jsblock' => array());
        }
        foreach ($this->_library as $l) {
            echo "\n";
            switch ($l[1]) {
                case 'css':
                    echo $this->Html->css($l[0], 'stylesheet', $l[2]);
                    break;
                case 'js':
                    echo $this->Javascript->link($l[0]);
                    break;
                case 'jsexternal':
                    //ROS_TODO: linkOut Deprecated in 1.2
                    echo $this->Javascript->linkOut($l[0]);
                    break;
                case 'jsblock':
                    if ($this->cache) {
                        $cached['jsblock'][] = $l[0];
                    } else {
                        echo $this->_codeBlock($l[0]);
                    }
                    break;
                case 'meta':
                    echo "<meta " . $this->Util->parse_attributes($l[0]) . " />";
                    break;
                case 'link':
                    echo "<link " . $this->Util->parse_attributes($l[0]) . " />";
                    break;
                case 'raw':
                    echo $l[0];
                    break;
                case 'cssblock':
                    echo '<style type="text/css" ' . $this->Util->parse_attributes($l[2]) . " ><!--{$l[0]}--></style>";
                    break;
                default:
                    $this->log("Internal error on HeadHelper: Unknown type registered: {$l[1]} at {$l[0]}");
            }
            echo "\n";

        }
        if ($this->cache) {
            //print cached jsblock
            echo $this->_codeBlock(implode("\n", $cached['jsblock'])) . "\n";
        }
    }


    /**
     * Adds the item in the array if it doesn't already exist
     * @param array $item Item to be added
     * @access private
     */
    function _register($item)
    {
        if (!in_array($item, $this->_library)) {
            $this->_library[] = $item;
        }
    }

    //ROS_TODO: Remove when unnecessary
    function _codeBlock($jsblock)
    {
        $jsblock = "<!--\n$jsblock\n-->";
        return $this->Javascript->codeBlock($jsblock);
    }
}

