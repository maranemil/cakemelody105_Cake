<?php
/**
 * CJS Templates View 
 * Renders Javascript with PageHelper and JavascriptHelper 
 *
 * The templates rendered must have extension .cjs
 * 
 * @author RosSoft
 * @version 0.21
 * @license MIT
 */
class CjsView extends View 
{	
	function render($action = null, $layout = null, $file = null) 
	{
		header('Content-type: text/html;charset=UTF-8');
		$this->autoLayout=false; 
		$file=null;       
        
        $this->load_helper('Javascript');
        $this->load_helper('Page');       	    
	    
		if ($action==NULL)
		{
			$action=$this->action;
		}
	    $file=$this->get_filename($action,'.cjs');
	    ob_start();	    
		parent::render($action,$layout,$file);
		echo $this->loaded['javascript']->codeBlock(ob_get_clean());
	}	
	
	function load_helper($helper)
	{
		if (!in_array($helper,$this->helpers))
		{
			$this->helpers[]=$helper;
		}
	}
	
	/**
	 * Returns the filename associated with the action
	 * with the extension ".$ext"
	 * 
	 * @param string $action If null, then current action
	 * @param string $ext Extension of the view template (with dot) Example: '.xml'
	 * @return string
	 */
	function get_filename($action,$ext)
	{		
		$old_ext=$this->ext;
		$this->ext=$ext;
		$fn=$this->_getViewFileName($action);
		$this->ext=$old_ext;
		return $fn;
	}
	
}
?>