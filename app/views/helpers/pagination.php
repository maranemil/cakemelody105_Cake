<?php
/**
 * Pagination Helper, responsible for managing the LINKS required for pagination.
 * ALL parameters are specified in the component.
 * @package cheeseCake
 */
class PaginationHelper extends Helper
{
	var $helpers = array('Ajax','Html');
/**
 * Placeholder for the link style - defined in/by the component
 *
 * @var boolean
 * @access private
 */
	var $style = 'html';
/**
 * Placeholder for the parameter style - defined in/by the component
 *
 * @var boolean
 * @access private
 */
	var $paramStyle = 'get';
/**
 * Placeholder for the pagination details
 *
 * @var array
 * @access private
 */
	var $_pageDetails = Array();

/**
 * Sets the default pagination options. Fails if the value $paging is not set
 *
 * @param array $paging an array detailing the page options
 * @return boolean
 */
	function setPaging($paging)
	{
		if (empty($paging)) {return false;}

		$this->_pageDetails = $paging;
		$this->_pageDetails['previousPage'] = ($paging['page']>1) ? $this->_pageDetails['page']-1 : '';
		$this->_pageDetails['nextPage'] = ($paging['page'] < $this->_pageDetails['pageCount']) ? $this->_pageDetails['page']+1 : '';

		$this->url = $this->_pageDetails['url'];

		$getParams = $this->params['url'];
		unset($getParams['url']);
		$this->getParams = $getParams;

		$this->showLimits = $this->_pageDetails['showLimits'];
		$this->style = isset($this->_pageDetails['style'])?$this->_pageDetails['style']:$this->style;

		if (($this->_pageDetails['maxPages'] % 2)==0) // need odd number of page links
		{
			$this->_pageDetails['maxPages'] = $this->_pageDetails['maxPages']-1;
		}

		$this->maxPages = $this->_pageDetails['maxPages'];
		$this->pageSpan = ($this->_pageDetails['maxPages']-1)/2;

   		return true;
	}

/**
* Displays the list of pages for the given parameters.
*
* @param string text to display before limits
* @param string display a separate between limits
* @param boolean whether to escape the title or not
* @return unknown the html string for modifying the number of results per page
**/
	function resultsPerPage($text=null, $separator=null,$escapeTitle=true)
	{
		if (empty($this->_pageDetails)) { return false; }
		if ( !empty($this->_pageDetails['pageCount']) )
		{
			$t = '';
			if(is_array($this->_pageDetails['resultsPerPage']))
			{
				$OriginalValue = $this->_pageDetails['show'];
				$t = $text.$separator;
				foreach($this->_pageDetails['resultsPerPage'] as $value)
				{
					if($OriginalValue == $value)
					{
						$t .= '<em>'.$value.'</em>'.$separator;
					}
					else
					{
						$this->_pageDetails['show'] = $value;
						$t .= $this->_generateLink($value,1,$escapeTitle).$separator;
					}
				}
				$this->_pageDetails['show'] = $OriginalValue;
			}
			return $t;
		}
		return false;
	}
/**
* Displays info of the current result set
*
* @param string
* @param string
* @param string
* @param string
* @return unknown the html string for the current result set.
**/
	function result($text="Results per page: ",$of=" of ",$inbetween="-")
	{
		if (empty($this->_pageDetails)) { return false; }

		if ( !empty($this->_pageDetails['pageCount']) )
		{
			if($this->_pageDetails['pageCount'] > 1)
			{
				$start_row = (($this->_pageDetails['page']-1)*$this->_pageDetails['show'])+1;
				$end_row = min ((($this->_pageDetails['page'])*$this->_pageDetails['show']),($this->_pageDetails['total']));
				$t = $text.$start_row.$inbetween.$end_row.$of.$this->_pageDetails['total'];
			}
			else
			{
				$t = $text.$this->_pageDetails['total'];
			}
			return $t;
		}
		return false;
	}
/**
* Returns a list of page numbers separated by $separator
*
* @param string $separator - defaults to null
* @param boolean
* @param string $spacerLower - If there are more results than space for the links, the text inbetween
* @param string $spacerUpper - If there are more results than space for the links, the text inbetween
* @return string html for the list of page numbers
**/
	function pageNumbers($separator=null,$escapeTitle=true,$spacerLower="...",$spacerUpper="...")
	{
		if (empty($this->_pageDetails) || $this->_pageDetails['pageCount'] == 1) { return "<em>1</em>"; }

		$total = $this->_pageDetails['pageCount'];
		$max = $this->maxPages;
		$span = $this->pageSpan;
		if ($total<$max)
		{
			$upperLimit = min($total,($span*2+1));
			$lowerLimit = 1;
		}
		elseif ($this->_pageDetails['page']<($span+1))
		{
			$lowerLimit = 1;
			$upperLimit = min($total,($span*2+1));
		}
		elseif ($this->_pageDetails['page']>($total-$span))
		{
			$upperLimit = $total;
			$lowerLimit = max(1,$total-$span*2);
		}
		else
		{
			$upperLimit = min ($total,$this->_pageDetails['page']+$span);
			$lowerLimit = max (1,($this->_pageDetails['page']-$span));
		}

		$t = array();
		if (($lowerLimit<>1)AND($this->showLimits))
		{
			$lowerLimit = $lowerLimit+1;
			$t[] = $this->firstPage(1,$escapeTitle);
			if ($spacerLower)
			{
				$t[] = $spacerLower;
			}
		}
		if (($upperLimit<>$total)AND($this->showLimits))
		{
			$dottedUpperLimit = true;
		}
		else
		{
			$dottedUpperLimit = false;
		}
		if (($upperLimit<>$total)AND($this->showLimits))
		{
			$upperLimit = $upperLimit-1;
		}
		for ($i = $lowerLimit; $i <= $upperLimit; $i++)
		{
			 if($i == $this->_pageDetails['page'])
			 {
				$text = '<em>'.$i.'</em>';
			 }
			 else
			 {
				$text = $this->_generateLink($i,$i,$escapeTitle);
			 }
			 $t[] = $text;
		}
		if ($dottedUpperLimit)
		{
			if ($spacerUpper)
			{
				$t[] = $spacerUpper;
			}
			$t[] = $this->lastPage($this->_pageDetails['pageCount'],$escapeTitle);
		}
		$t = implode($separator, $t);
		return $t;
	}

/**
* Displays a link to the previous page, where the page doesn't exist then
* display the $text
*
* @param string $text - text display: defaults to next
* @return string html for link/text for previous item
**/
	function prevPage($text='prev',$escapeTitle=true,$htmlAttributes="")
	{
		if (empty($this->_pageDetails)) { return false; }
		if ( !empty($this->_pageDetails['previousPage']) )
		{
			return $this->_generateLink($text,$this->_pageDetails['previousPage'],$escapeTitle,'content',$htmlAttributes);
		}
		return $text;
	}

/**
* Displays a link to the next page, where the page doesn't exist then
* display the $text
*
* @param string $text - text to display: defaults to next
* @return string html for link/text for next item
**/
	function nextPage($text='next',$escapeTitle=true,$htmlAttributes="")
	{
		if (empty($this->_pageDetails)) { return false; }
		if (!empty($this->_pageDetails['nextPage']))
		{
			return $this->_generateLink($text,$this->_pageDetails['nextPage'],$escapeTitle,'content',$htmlAttributes);
		}
		return $text;
	}

/**
* Displays a link to the first page
* display the $text
*
* @param string $text - text to display: defaults to next
* @return string html for link/text for next item
**/
	function firstPage($text='first',$escapeTitle=true)
	{
		if (empty($this->_pageDetails)) { return false; }
		if ($this->_pageDetails['page']<>1)
		{
			return $this->_generateLink($text,1,$escapeTitle);
		}
		else
		{
			return false;
		}
	}

/**
* Displays a link to the last page
* display the $text
*
* @param string $text - text to display: defaults to next
* @return string html for link/text for next item
**/
	function lastPage($text='last',$escapeTitle=true)
	{
		if (empty($this->_pageDetails)) { return false; }
		if ($this->_pageDetails['page']<>$this->_pageDetails['pageCount'])
		{
			return $this->_generateLink($text,$this->_pageDetails['pageCount'],$escapeTitle);
		}
		else
		{
			return false;
		}
	}


/**
* Generate link to sort the results by the given value
*
* @param string field to sort by
* @param string title for link defaults to $value
* @param string model to sort by - uses the default model class if absent
* @param boolean escape title
* @param string text to append to links to indicate sorted ASC
* @param string text to append to links to indicate sorted DESC
* @return string html for link to modify sort order
**/
	function sortBy ($value, $title=NULL, $Model=NULL,$escapeTitle=true,$upText=" ^",$downText=" v")
	{
		$title = $title?$title:$value;
		$value = strtolower($value);
		$Model = $Model?$Model:$this->_pageDetails['Defaults']['sortByClass'];

		$OriginalSort = $this->_pageDetails['sortBy'];
		$OriginalModel = $this->_pageDetails['sortByClass'];
		$OriginalDirection = $this->_pageDetails['direction'];
		//echo "does $value = $OriginalSort<br>";
		//echo "does '$Model' = '$OriginalModel'<br>";

		if (($value==$OriginalSort)&&($Model==$OriginalModel))
		{
			if (up($OriginalDirection)=="DESC")
			{
				$this->_pageDetails['direction'] = "ASC";
				$title .= $upText;
			}
			else
			{
				$this->_pageDetails['direction'] = "DESC";
				$title .= $downText;
			}
		}
		else
		{
			if ($Model)
			{
				$this->_pageDetails['sortByClass'] = $Model;
				//echo "page details model class set to ".$this->_pageDetails['sortByClass']."<br>";
			}
			else
			{
				$this->_pageDetails['sortByClass'] = NULL;
			}
			$this->_pageDetails['sortBy'] = $value;
		}
		$link = $this->_generateLink ($title,1,$escapeTitle);
		$this->_pageDetails['sortBy'] = $OriginalSort;
		$this->_pageDetails['sortByClass'] = $OriginalModel;
		$this->_pageDetails['direction'] = $OriginalDirection;
		return $link;
	}

/**
* Internal method to generate links based upon passed parameters.
*
* @param string title for link
* @param string page the page number
* @param boolean escape title
* @param string the div to be updated by AJAX updates
* @return string html for link
**/
	function _generateLink ($title,$page=NULL,$escapeTitle,$AjaxDivUpdate="content", $htmlAttributes='')
	{
		$getParams = $this->getParams; // Import any other pre-existing get parameters

		if ($this->_pageDetails['paramStyle']=="pretty")
		{
			$pageParams=$this->_pageDetails['importParams'];
		}
		$pageParams['show'] = $this->_pageDetails['show'];
		$pageParams['sortBy'] = $this->_pageDetails['sortBy'];
		$pageParams['direction'] = $this->_pageDetails['direction'];
		$pageParams['page'] = $page?$page:$this->_pageDetails['page'];
		if (isset($this->_pageDetails['sortByClass']))
		{
			$pageParams['sortByClass'] = $this->_pageDetails['sortByClass'];
		}
		return $this->__generateLink($title,$escapeTitle,$getParams,$pageParams,$AjaxDivUpdate,$htmlAttributes);
	}

/**
* Private/Internal method to generate links called by _generateLink only
*
* @param string title for link
* @param boolean escape title
* @param array the get parameters present for the original page request
* @param array the pagination parameters necessary to be included in the generate link
* @param string the div to be updated by AJAX updates
* @return string html for link
**/
	function __generateLink ($title="ErrorNeverBlank",$escapeTitle,$getParams=Array("error"=>"NeverEmpty"),$pageParams=Array("error"=>"NeverEmpty"),$AjaxDivUpdate="content",$htmlAttributes='')
	{
		$getString = Array();
		$prettyString = Array();
		if ($this->_pageDetails['paramStyle']=="get")
		{
			$getParams = am($getParams,$pageParams);
		}
		else
		{
			foreach($pageParams as $key => $value)
			{
				if (isset($this->_pageDetails['Defaults'][$key]))
				{
					if (up($this->_pageDetails['Defaults'][$key])<>up($value))
					{
						$prettyString[] = "$key/$value";
					}
				}
				else
				{
					$prettyString[] = "$key/$value";
				}
			}
		}
		foreach($getParams as $key => $value)
		{
			if ($this->_pageDetails['paramStyle']=="get")
			{
				if (isset($this->_pageDetails['Defaults'][$key]))
				{
					if (up($this->_pageDetails['Defaults'][$key])<>up($value))
					{
						$getString[] = "$key=$value";
					}
				}
				else
				{
					$getString[] = "$key=$value";
				}
			}
			else
			{
				$getString[] = "$key=$value";
			}
		}
		$url = $this->url;
		if ($prettyString)
		{
			$prettyString = implode ("/", $prettyString);
			$url .= $prettyString;
		}
		if ($getString)
		{
			$getString = implode ("&amp;", $getString);
			$url .= "?".$getString;
		}

		if (($this->style=="ajax")&&(isset($this->Ajax)))
		{
			return $this->Ajax->link(
							$title,
							$url,
								array(
									"update" => $AjaxDivUpdate
									),
							NULL,
							NULL,
							$escapeTitle
								);
		}
		else
		{
			return $this->Html->link(
							$title,
							$url,
							$htmlAttributes,
							NULL,
							$escapeTitle
							);
		}
	}
}
?>
