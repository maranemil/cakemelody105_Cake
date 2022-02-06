<?php /** @noinspection PhpUnused */
/**
 * Page Helper
 * Similar to RJS Templates.
 * It has some wrapper functions for prototype + scriptaculous
 * Requires Prototype >= 1.4.0
 * Requires HeadHelper >= 0.5
 * Requires UtilHelper >= 0.12
 * @author          RosSoft
 * @version         0.66
 * @license         MIT
 * API of RoR-RJS
 * @link            http://api.rubyonrails.org/classes/ActionView/Helpers/PrototypeHelper/JavaScriptGenerator/GeneratorMethods.html
 * Prototype Library
 * @link            http://prototype.conio.net/
 * Examples of RJS
 * @link            http://rails.techno-weenie.net/tip/2005/11/29/ajaxed_forms_with_rjs_templates
 * In the API, a css selector can be a string or an array
 * of strings. The format of the string is a prototype css
 * selection.
 * Examples:
 * $css='.cl'   All the elements with class 'cl'
 * $css='#someid'   Element with id 'someid'
 * $css=array('#someid','.cl') All elements with class 'cl' union element with id 'someid'
 * $css='p.cl'  All the <p class="cl"> elements
 * In PHP4:
 * echo $page->effect('#someid','Hightlight');
 * In PHP5 you can also
 * echo $page['#someid']->effect('Highlight');
 * @package         helpers
 */

/**
 * The DOM ID for the DIV container of PageHelper responses
 * This HTML DIV will be created automatically
 */
const PAGE_CONTAINER = 'page_container';

/**
 * Main class
 * @property $Ajax
 * @property $Head
 * @property $Javascript
 * @property $view
 * @property $Util
 * @property $Html
 */
class PageMain extends Helper
{
    public $helpers
                       = array('Head',
                            'Javascript',
                            'Ajax',
                            'Html',
                            'Util'
        );
    public $_instances = array();

    /**
     * If true, then all the calls will be attached to
     * an onload function
     */
    public $enclose_onload = false;

    /**
     * Enable enclosing in <script> tags.
     * For enclosing, $enclose_enable and the parameter
     * $enclose of a call must be true both
     */
    public $enclose_enable = true;

    /**
     * Registers the necessary files
     */
    public function afterRender()
    {
        if (!$this->isAjax()) {
            $new_element = '<div id="' . PAGE_CONTAINER . '" style="display: none;"/>';
            $this->Head->onload(
                $this->insert_html('Bottom', '$(document.body)', $new_element, false)
            );
        }
    }

    /**
     * Returns true if the current call is from Ajax, false otherwise
     * @return bool True if call is Ajax
     */
    //ROS_TODO: Eliminar de aqui y todos DebugwindowHelper
    public function isAjax()
    {
        if (env('HTTP_X_REQUESTED_WITH') !== null) {
            return env('HTTP_X_REQUESTED_WITH') === "XMLHttpRequest";
        }

        return false;
    }

    /**
     * Enclose or not the javascript code
     *
     * @param string $jscode Javascript to be [not] enclosed
     * @param boolean $enclose
     *
     * @return string
     */
    public function _enclose($jscode, $enclose)
    {
        /*
         * Enclosing in <script> will be disabled if the
         * view is CjsView, because all output will be enclosed by CjsView in only one block
         */
        if ($this->enclose_enable
            && $enclose
            && !strcasecmp(get_class($this->view), 'CjsView') === 0) {
            if ($this->enclose_onload) {
                $jscode = $this->on_load($jscode, false);
            }
            return $this->Javascript->codeBlock($jscode) . "\n";
        }

        return $jscode . "\n";
    }

    /**
     * Replaces the inner HTML code of some objects
     *
     * @param mixed $css CSS selector of the objects
     * @param string $content The content to be set
     * @param boolean $enclose
     *
     * @return string
     */
    public function replace_html($css, $content, $enclose = true)
    {
        $js = 'Element.update(_elem,' . $this->_js_string($content) . ');';
        return $this->for_each($css, $js, $enclose);
    }

    /**
     * Replaces the inner HTML code of some objects with an element
     *
     * @param mixed $css CSS selector of the objects
     * @param string $element The name of the element
     * @param string $params The params of the element
     * @param boolean $enclose
     *
     * @return string
     */
    public function replace_render_element($css, $element, $params = array(), $enclose = true)
    {
        $content = $this->view->renderElement($element, $params);
        return $this->replace_html($css, $content, $enclose);
    }

    /**
     * Replaces the inner HTML code of some objects with a requestAction
     *
     * @param mixed $css CSS selector of the objects
     * @param string $url RequestAction url
     * @param string $params Extra params to RequestAction
     * @param boolean $enclose
     *
     * @return string
     */
    public function replace_request_action($css, $url, $params = array(), $enclose = true)
    {
        $params['return'] = true;
        $content = $this->requestAction($url, $params);
        return $this->replace_html($css, $content, $enclose);
    }

    /**
     * Replaces the outer HTML code of some objects (replaces the entire object)
     *
     * @param mixed $css CSS selector of the objects
     * @param string $content HTML tag and content to be set
     * @param boolean $enclose
     *
     * @return string
     */
    public function replace($css, $content, $enclose = true)
    {
        $js = 'Element.replace(_elem,' . $this->_js_string($content) . ');';
        return $this->for_each($css, $js, $enclose);
    }

    /**
     * Returns a reference to javascripts elements
     *
     * @param mixed $css CSS selector / array of selectors
     *
     * @return string Javascript reference
     */
    public function _ref($css)
    {
        if (is_object($css) && strcasecmp(get_class($css), 'PageProxy') === 0) {
            /* The parameter is an instance of a page proxy
                get real css selector from it */
            return $css->ref();
        }

        $js = "$$('";
        if (is_array($css)) {
            $js .= implode("','", $css);
        } else {
            $js .= $css;
        }
        $js .= "')";
        return $js;
    }

    /**
     * Returns a javascript proxy object to the css selection
     *
     * @param mixed $css CSS selection
     *
     * @return object Proxy to the object
     */
    public function e($css)
    {
        //The proxy object is saved for "caching"
        $offset = md5(serialize($css));
        if (!isset($this->_instances[$offset])) {
            $this->_instances[$offset] = new PageProxy($this, $css);
        }
        return $this->_instances[$offset];
    }

    /**
     * Executes the effect to some elements that matches css selection
     *
     * @param mixed $css CSS selection
     * @param string $effect The scriptaculous effect (Fade,Appear,...)
     * @param array $params Extra parameters for the effect
     * @param boolean $enclose
     *
     * @return string
     * @link http://wiki.script.aculo.us/scriptaculous/show/CombinationEffectsDemo
     */
    public function effect($css, $effect, $params = array(), $enclose = true)
    {
        $params = $this->Javascript->object($params);
        $effect = Inflector::camelize($effect);
        return $this->for_each($css, "new Effect.$effect(_elem,$params)", $enclose);
    }

    /**
     * Executes the jscode for each element that matches
     * the css selection. The jscode is executed for each
     * element, the element is referenced by the variable '_elem'
     *
     * @param mixed $css CSS selection
     * @param string $jscode The javascript code using _elem variable
     * @param boolean $enclose
     *
     * @return string
     */
    public function for_each($css, $jscode, $enclose = true)
    {
        $ref = $this->_ref($css);

        //if it's an DOM ID, then use $(id) instead of $$(css_selector)
        $matches = array();
        if (preg_match('/^\$\$\(\'#(\w+)\'\)$/', $ref, $matches)) {
            $js = "_elem=$('$matches[1]'); $jscode";
        } else {
            $js = "$ref.each(function(_elem) { $jscode });";
        }
        return $this->_enclose($js, $enclose);
    }

    /**
     * Safe javascript string.
     * Must be decoded with the js function 'unescape'
     *
     * @param string PHP String
     *
     * @return string Javascript-safe string.
     */
    public function _js_string($string)
    {
        /*
            $js_string=rawurlencode(utf8_decode($string));
            return 'unescape("' . $js_string . '")';
        */
        $string = str_replace(array('%', '/', '<', "\\", "\r", "\n", '"'),
                              array('%25', '\/', '%3C', '\\', '', '\r\n', '\"'),
                              $string);
        return 'unescape("' . $string . '")';
    }

    /**
     * Attach an event to an element.
     *
     * @param mixed $css CSS selector to the element to be observed
     * @param string $event event to observe
     * @param string $jscode function to call
     * @param boolean $enclose
     *
     * @return string
     */
    public function event($css, $event, $jscode, $enclose = true)
    {
        $js_event = "Event.observe(_elem, \"$event\", function(event){ $jscode }, false);";
        return $this->for_each($css, $js_event, $enclose);
    }

    /**
     * Attaches an onload function
     *
     * @param string $jscode Javascript code to be executed
     * @param boolean $enclose
     *
     * @return string
     */
    public function on_load($jscode, $enclose = true)
    {
        //Not works with $this->event (I don't know a css selector for window)
        $js = "Event.observe(window, \"load\", function(){ $jscode }, false);";
        return $this->_enclose($js, $enclose);
    }

    /**
     * Hides an element or an array of elements by css selector
     *
     * @param mixed $css CSS selector
     * @param bool $enclose
     *
     * @return string
     */
    public function hide($css, $enclose = true)
    {
        return $this->for_each($css, "Element.hide(_elem)", $enclose);
    }

    /**
     * Shows an element or an array of elements by css selector
     *
     * @param mixed $css CSS Selector
     * @param boolean $enclose
     *
     * @return string
     */
    public function show($css, $enclose = true)
    {
        return $this->for_each($css, "Element.show(_elem)", $enclose);
    }

    /**
     * Removes an element or an array of elements by css selector
     *
     * @param mixed $css Selector
     * @param boolean $enclose
     *
     * @return string
     */
    public function remove($css, $enclose = true)
    {
        return $this->for_each($css, "Element.remove(_elem)", $enclose);
    }

    /**
     * Toggles the visibility of an element or an array of elements
     *
     * @param         $css
     * @param boolean $enclose
     *
     * @return string
     */
    public function toggle($css, $enclose = true)
    {
        return $this->for_each($css, "Element.toggle(_elem)", $enclose);
    }

    /**
     * Returns an input submit tag for ajax submit
     *
     * @param string $caption Button's caption
     * @param array $options Ajax options
     *
     * @see Page::remote_url
     *
     * @param array $htmlOptions HTML options for the button
     *
     * @return string HTML input tag
     */
    public function submit($caption, $options = array(), $htmlOptions = array())
    {
        if (!isset($options['update'])) {
            $options['update'] = PAGE_CONTAINER;
        }
        //AjaxHelper::submit only uses 1 array
        $options = am($options, $htmlOptions);
        return $this->Ajax->submit($caption, $options);
    }

    /**
     * Javascript redirection to the given location
     *
     * @param string $url Absolute url or Cake url
     * @param boolean $is_cakeurl If true, then $url is a Cake url
     * @param boolean $enclose
     *
     * @return string
     */
    public function redirect($url, $is_cakeurl = true, $enclose = true)
    {
        if ($is_cakeurl) {
            $url = $this->Util->to_absolute_url($url);
        }
        $js = "window.location.href='$url'";
        return $this->_enclose($js, $enclose);
    }

    /**
     * Periodically calls some javascript code
     *
     * @param string $jscode Javascript to be called
     * @param integer $freq Seconds
     * @param bool $enclose
     *
     * @return string
     */
    public function periodical($jscode, $freq = 10, $enclose = true)
    {
        $js = "new PeriodicalExecuter(function() { $jscode }, $freq)";
        return $this->_enclose($js, $enclose);
    }

    /**
     * Periodically calls a remote url (useful for doing some javascript, etc.)
     *
     * @param string $url Cakeway /controller/action
     * @param integer $freq Frequency in seconds
     * @param string $form_id DOM Id of the form to send serialized in Ajax request or NULL
     * @param array $options @see AjaxHelper
     *                         If $options['update'] div not set, then it will be a hidden one
     * @param boolean $enclose
     *
     * @return string
     */
    public function periodical_remote($url, $freq = 10, $form_id = null, $options = array(), $enclose = true)
    {
        if ($form_id !== null) {
            $options['with'] = "Form.serialize('$form_id')";
        }
        $js = $this->remote_url($url, $options, false);
        return $this->periodical($js, $freq, $enclose);
    }

    /**
     * Observes a form, on any change it calls a remote url (useful for doing some javascript, etc.)
     *
     * @param string $url Cakeway /controller/action
     * @param string $form_id DOM Id of the form to be observed and sent serialized in Ajax request
     * @param integer $freq Frequency in seconds
     * @param array $options @see AjaxHelper
     *                         If $options['update'] div not set, then it will be a hidden one
     * @param boolean $enclose
     *
     * @return string
     */
    public function observe_form($url, $form_id = 'form', $freq = 2, $options = array(), $enclose = true)
    {
        $options['with'] = "Form.serialize('$form_id')";
        return $this->_observe_aux('Form.Observer', $form_id, $url, $freq, $options, $enclose);
    }

    /**
     * Observes a form, on any change it calls a remote url (useful for doing some javascript, etc.)
     *
     * @param string $url Cakeway /controller/action
     * @param string $field_id DOM Id of the field to be observed and sent serialized in Ajax request
     * @param integer $freq Frequency in seconds
     * @param array $options @see AjaxHelper
     *                          If $options['update'] div not set, then it will be a hidden one
     * @param boolean $enclose
     *
     * @return string
     * TODO: Untested
     */
    public function observe_field($url, $field_id, $freq = 2, $options = array(), $enclose = true)
    {
        $options['with'] = "Form.Element.serialize('$field_id')";
        return $this->_observe_aux('Form.Element.Observer', $field_id, $url, $freq, $options, $enclose);
    }

    /**
     * Auxiliar function for observe_form and observe_field
     *
     * @param string $observe_class Name of the javascript observer class
     * @param string $id DOM Id of the element to be observed
     * @param string $url Cakeway /controller/action
     * @param integer $freq Frequency in seconds
     * @param array $options @see AjaxHelper
     * @param boolean $enclose
     *
     * @return string
     */
    public function _observe_aux($observe_class, $id, $url, $freq, $options, $enclose)
    {
        $js_remote_call = $this->remote_url($url, $options, false);
        $js = "new $observe_class('$id',$freq,function(){ $js_remote_call })";
        return $this->_enclose($js, $enclose);
    }

    /**
     * Periodically replaces a DIV with the content of a remote url response
     *
     * @param string $id DOM id of the replaced DIV
     * @param string $url Cakeway /controller/action
     * @param integer $freq Frequency in seconds
     * @param array $options @see AjaxHelper
     * @param boolean $enclose
     *
     * @return string
     */
    public function periodical_remote_div($id, $url, $freq = 10, $options = array(), $enclose = true)
    {
        $options['update'] = $id;
        $js = $this->remote_url($url, $options, false);
        return $this->periodical($js, $freq, $enclose);
    }

    /**
     * Calls a remote url (useful for updating a div)
     *
     * @param string $url Cakeway /controller/action
     * @param array $options @see AjaxHelper
     *                         If $options['update'] div not set, then it will be a hidden one
     * @param boolean $enclose
     *
     * @return string
     */
    public function remote_url($url, $options = array(), $enclose = true)
    {
        $options['url'] = $url;
        if (!isset($options['update'])) {
            $options['update'] = PAGE_CONTAINER;
        }
        $js = $this->remote_function($options, false);
        return $this->_enclose($js, $enclose);
    }

    /**
     * Returns an anchor link to a CJS remote Action
     *
     * @param string $caption Caption for the link
     * @param string $url Cake Url like /controller/action/param1/param2
     * @param array $options Options for the remote_url
     * @param array $html_options Extra html options for the html link (like class, style)
     *
     * @return string HTML link
     * @see Page::remote_url
     *        $options['form'] =>DOM Id of the form to be posted in the ajax call (optional)
     */
    public function link_remote_url($caption, $url, $options = array(), $html_options = array())
    {
        if (isset($options['form'])) {
            $form_id = $options['form'];
            unset($options['form']);
            $options['with'] = "Form.serialize('$form_id')";
        }
        $html_options['onclick'] = $this->remote_url($url, $options, false);
        return $this->Html->link($caption, 'javascript:void(0)', $html_options);
    }

    /**
     * Calls a remote ajax function
     *
     * @param array $options @see AjaxHelper
     * @param boolean $enclose
     *
     * @return string
     */
    public function remote_function($options = array(), $enclose = true)
    {
        if (!isset($options['requestHeaders'])) {
            $options['requestHeaders'] = array();
        }
        $options['requestHeaders'] = am($options['requestHeaders'], array('X-CJS-Templates' => 1));
        $js = $this->Ajax->remoteFunction($options);
        return $this->_enclose($js, $enclose);
    }

    /**
     * Executes the jscode after the delay
     *
     * @param string $jscode Javascript to be executed
     * @param integer $delay Delay time in seconds
     * @param boolean $enclose
     *
     * @return string
     */
    public function delay($jscode, $delay, $enclose = true)
    {
        $js = "setTimeout(function() {\n$jscode\n}," . ($delay * 1000) . ');';
        return $this->_enclose($js, $enclose);
    }

    /**
     * Converts an array of args to a js-array of args
     *
     * @param array $args
     *
     * @return string
     */
    public function _call_args($args)
    {
        $arr = array();
        foreach ($args as $i) {
            $arr[] = $this->_object($i);
        }
        return implode(",", $arr);
    }

    /**
     * Calls a js function with the given array of params
     *
     * @param string $function Name of the function
     * @param array $args Array of params
     * @param boolean $enclose
     *
     * @return string
     */
    public function call($function, $args = array(), $enclose = true)
    {
        $js = "$function(" . $this->_call_args($args) . ");";
        return $this->_enclose($js, $enclose);
    }

    /**
     * Shows an alert box
     *
     * @param string $message Message to be shown
     * @param boolean $enclose
     *
     * @return string
     */
    public function alert($message, $enclose = true)
    {
        return $this->call('alert', array($message), $enclose);
    }

    /**
     * Returns the js-string of a php variable
     *
     * @param mixed $var
     *
     * @return string
     */
    public function _object($var)
    {
        if ($var instanceof PageProxy) {
            return $var->js_object();
        }

        if (is_array($var)) {
            return $this->Javascript->object($var);
        }

        $var = $this->Javascript->escapeString($var);
        return "'$var'";
    }

    /**
     * Assigns a value to a js variable
     *
     * @param string $variable name of the variable
     * @param string $value
     *
     * @return string
     * ROS_TODO: Untested
     */
    public function assign($variable, $value)
    {
        $js = "$variable=" . $this->_object($value);
        return $this->_enclose($js);
    }

    /**
     * Adds a CSS class to an element or an array of elements by css selector
     *
     * @param string $css Selector
     * @param string $classname Class to be added
     * @param boolean $enclose
     *
     * @return string
     */
    public function add_class($css, $classname, $enclose = true)
    {
        return $this->for_each($css, "Element.addClassName(_elem,'$classname');", $enclose);
    }

    /**
     * Removes a CSS class to an element or an array of elements by css selector
     *
     * @param string $css Selector
     * @param string $classname Class to be removed
     * @param boolean $enclose
     *
     * @return string
     */
    public function remove_class($css, $classname, $enclose = true)
    {
        return $this->for_each($css, "Element.removeClassName(_elem,'$classname');", $enclose);
    }

    /**
     * Inserts html string $position relative to DOM object $id
     *
     * @param string $position can be:
     *                          Top: at the top of the element's content
     *                          Bottom: at the bottom of the element's content
     *                          Before: before the element
     *                          After: after the element
     * @param string $id DOM id of the object reference. You can use $(document.body) for special cases
     * @param         $html
     * @param boolean $enclose
     *
     * @return string
     */
    public function insert_html($position, $id, $html, $enclose = true)
    {
        //Enable use of $(document.body) (must not be quoted)
        if (strpos($id, '$') !== 0) {
            $id = "'" . $id . "'";
        }
        $html = $this->_object($html);
        $js = "new Insertion.$position($id,$html);";
        return $this->_enclose($js, $enclose);
    }

    /**
     * Enables the drag of element(s)
     *
     * @param mixed $css CSS selector / array of selectors
     * @param array $options Options for drag
     * @param boolean $enclose
     *
     * @return string
     */
    public function draggable($css, $options = array(), $enclose = true)
    {
        if (!isset($options['revert'])) {
            $options['revert'] = true;
        }
        $js = "new Draggable(_elem, " . $this->Ajax->_optionsForDraggable($options) . ');';
        return $this->for_each($css, $js, $enclose);
    }

    /**
     * Enables the drop receiving of element(s)
     *
     * @param mixed $css CSS selector / array of selectors of droppables
     * @param string $accept The class of draggables that accepts
     * @param string $url Cake URL of ajax call
     * @param array $options Options for droppables (null for default)
     * @param array $ajaxOptions Options for ajax call(null for default)
     * @param boolean $enclose
     *
     * @return string
     */
    public function droppable($css, $accept, $url, $options = array(), $ajaxOptions = array(), $enclose = true)
    {
        if (!isset($ajaxOptions['with'])) {
            $ajaxOptions['with'] = "'id='+encodeURIComponent(element.id) + '&drop=' + encodeURIComponent(_elem.id)  + '&drop_class=' + encodeURIComponent(_elem.className)";
        }

        $options['accept'] = $accept;

        if (!isset($options['onDrop'])) {
            $options['onDrop'] = "function(element){" . $this->remote_url($url, $ajaxOptions, false) . "}";
        }

        $options = $this->Ajax->_optionsForDroppable($options);

        $js = "Droppables.add(_elem, $options);";
        return $this->for_each($css, $js, $enclose);
    }

    /**
     * Makes a list or group of floated objects sortable.
     *
     * @param string $css CSS selector of parent
     * @param string $url Cake Url of ajax call
     * @param array $options Array of options for sort
     * @param array $ajaxOptions Array of options for ajax call
     * @param boolean $enclose
     *
     * @return string
     * @link http://wiki.script.aculo.us/scriptaculous/show/Sortable.create
     * ROS_TODO: Not works
     */
    public function sortable($css, $url, $options = array(), $ajaxOptions = array(), $enclose = true)
    {
        if (!isset($ajaxOptions['with'])) {
            $ajaxOptions['with'] = "Sortable.serialize(_elem)";
        }
        if (!isset($options['onUpdate'])) {
            $options['onUpdate'] = "function(element){" . $this->remote_url($url, $ajaxOptions, false) . "}";
        }
        $options = $this->Ajax->__optionsForSortable($options);
        $js = "Sortable.create(_elem, $options);";
        return $this->for_each($css, $js, $enclose);
    }

    // PHP5 ArrayAccess Interface
    public function offsetExists()
    {
        return true;
    }

    public function offsetGet($offset)
    {
        return $this->e($offset);
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetUnset($offset)
    {
    }
    //End PHP5 ArrayAccess Interface
}

////////////////////////////////////////////////////////////

/**
 * After creating an instance of it through
 * $obj=$page->e(css_selection),
 * you can call $obj->hide(), $obj->effect('Appear') ,etc.
 * @package helpers
 * @method js_object()
 */
class PageProxy extends Object
{
    /** css selector */
    public $_css;

    /**
     * Reference to the Page instance
     */
    public $Page;

    public function ref()
    {
        return $this->Page->_ref($this->_css);
    }

    public function add_classname($classname, $enclose = true)
    {
        return $this->Page->add_classname($this->_css, $classname, $enclose);
    }

    public function remove_classname($classname, $enclose = true)
    {
        return $this->Page->remove_classname($this->_css, $classname, $enclose);
    }

    /**
     * Constructor
     *
     * @param object $Page Instance of PageComponent
     * @param string $css CSS Selector
     */
    public function __construct(&$Page, $css)
    {
        parent::__construct();
        $this->Page =& $Page;
        $this->_css = $css;
    }

    /**
     * Shows the object(s)
     *
     * @param boolean $enclose
     *
     * @return mixed
     */
    public function show($enclose = true)
    {
        return $this->Page->show($this->_css, $enclose);
    }

    /**
     * Toggles the object(s)
     *
     * @param boolean $enclose
     *
     * @return mixed
     */
    public function toggle($enclose = true)
    {
        return $this->Page->toggle($this->_css, $enclose);
    }

    /**
     * Hides the object(s)
     *
     * @param boolean $enclose
     *
     * @return mixed
     */
    public function hide($enclose = true)
    {
        return $this->Page->hide($this->_css, $enclose);
    }

    /**
     * Removes the object(s)
     *
     * @param boolean $enclose
     *
     * @return mixed
     */
    public function remove($enclose = true)
    {
        return $this->Page->remove($this->_css, $enclose);
    }

    /**
     * Does an scriptaculous effect
     *
     * @param string $effect Name of effect
     * @param array $params Array of parameters to the effect
     * @param boolean $enclose
     *
     * @return mixed
     * @link http://wiki.script.aculo.us/scriptaculous/show/CombinationEffectsDemo
     */
    public function effect($effect, $params = array(), $enclose = true)
    {
        return $this->Page->effect($this->_css, $effect, $params, $enclose);
    }

    /**
     * Replaces the HTML code
     *
     * @param string $content The content to be set
     * @param boolean $enclose
     *
     * @return string
     */

    public function replace_html($content, $enclose = true)
    {
        return $this->Page->replace_html($this->_css, $content, $enclose);
    }

    /**
     * Attach an event to an element.
     *
     * @param string $event event to observe
     * @param string $jscode function to call
     * @param boolean $enclose
     *
     * @return string
     */
    public function event($event, $jscode, $enclose = true)
    {
        return $this->Page->event($this->_css, $event, $jscode, $enclose);
    }

    /**
     * Executes the jscode for each element that matches
     * the css selection. The jscode is executed for each
     * element, the element is referenced by the variable '_elem'
     *
     * @param string $jscode The javascript code using _elem variable
     * @param boolean $enclose
     *
     * @return string
     */
    public function for_each($jscode, $enclose = true)
    {
        return $this->Page->for_each($this->_css, $jscode, $enclose);
    }

    /**
     * Replaces the outer HTML code of the objects (replaces the entire object)
     *
     * @param string $content HTML tag and content to be set
     * @param boolean $enclose
     *
     * @return string
     */
    public function replace($content, $enclose = true)
    {
        return $this->Page->replace($this->_css, $content, $enclose);
    }

    /**
     * Replaces the inner HTML code of some objects with an element
     *
     * @param string $element The name of the element
     * @param string $params The params of the element
     * @param boolean $enclose
     *
     * @return string
     */
    public function replace_render_element($element, $params = array(), $enclose = true)
    {
        return $this->Page->replace_render_element($this->_css, $element, $params, $enclose);
    }

    /**
     * Replaces the inner HTML code of some objects with a requestAction
     *
     * @param string $url RequestAction url
     * @param string $params Extra params to RequestAction
     * @param boolean $enclose
     *
     * @return string
     */
    public function replace_request_action($url, $params = array(), $enclose = true)
    {
        return $this->Page->replace_request_action($this->_css, $url, $params, $enclose);
    }

    /**
     * Enables the drop receiving of element(s)
     *
     * @param string $accept The class of draggables that accepts
     * @param string $url Cake URL of ajax call
     * @param array $options Options for droppables (null for default)
     * @param array $ajaxOptions Options for ajax call(null for default)
     * @param boolean $enclose
     *
     * @return string
     */
    public function droppable($accept, $url, $options = array(), $ajaxOptions = array(), $enclose = true)
    {
        return $this->Page->droppable($this->_css, $accept, $url, $options, $ajaxOptions, $enclose);
    }

    /**
     * Enables the drag of element(s)
     *
     * @param array $options Options for drag
     * @param boolean $enclose
     *
     * @return string
     */
    public function draggable($options = array(), $enclose = true)
    {
        return $this->Page->draggable($this->_css, $options, $enclose);
    }

    /**
     * Makes a list or group of floated objects sortable.
     *
     * @param string $url Cake Url of ajax call
     * @param array $options Array of options for sort
     * @param array $ajaxOptions Array of options for ajax call
     * @param boolean $enclose
     *
     * @return string
     * @link http://wiki.script.aculo.us/scriptaculous/show/Sortable.create
     */
    public function sortable($url, $options = array(), $ajaxOptions = array(), $enclose = true)
    {
        return $this->Page->sortable($this->_css, $url, $options, $ajaxOptions, $enclose);
    }

}

/**
 * PageHelper Trick class
 * @access  private
 * @package helpers
 */

//Trick for auto php detection
if (PHP_VERSION >= 5) {
    eval('class PageHelper extends PageMain implements ArrayAccess
	{}');
} else {
    class PageHelper extends PageMain
    {
    }
}

