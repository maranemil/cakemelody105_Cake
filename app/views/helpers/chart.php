<?php

# Chart
# by Ludwig Pettersson
# <http://luddep.se>
# With help from Fredrik Holmström
# <http://loveandtheft.org/>
# Copyright (c) 2008 Ludwig Pettersson

class ChartHelper extends AppHelper 
{
	var $helpers = array('Html');
	// Constants
	var $googlepie = 'http://chart.apis.google.com/chart?';

	// Variables
	var $types = array(
							'pie' => 'p',
							'pie3d' => 'p3',
							'line' => 'lc',
							'sparkline' => 'ls',
							'bar-horizontal' => 'bhg',
							'bar-vertical' => 'bvg',
						);


			// Set graph colors
			/*
	public	$color = array(
							"#FF0000",
							"#FF3300",
							"#FF6600",
							"#FF9900",
							"#FFCC00",
							"#FFFF00",
							"#FF0033",
							"#FF3333",
							"#FF6633",
							"#FF9933",
							"#FFCC33",
							"#FFFF33"
						);
*/
	var $color = array(
						'#555555',
						'#FF0000',
						'#EA0D07',
						'#FFFF48',
						'#872D2D'
						);


	var $type;
	var $title;
	var $data = array();
	var $size = array();
	var $fill = array();
	var $labelsXY = false;
	var $legend;
	var $useLegend = false;
	var $background = 'a,s,ffffff';

	var $query = array();

	// debug
	var $debug = array();

	// Return string
	function __toString()
	{
		return $this->display();
	}


	/** Create chart
	*/
	function display()
	{
		// Create query
		$this->query = array(
							'cht'	 => $this->types[strtolower($this->type)],					// Type
							'chtt'	 => $this->title,											// Title
							'chd' => 't:'.$this->data['values'],								// Data
							'chl'   => $this->data['names'],									// Data labels
							'chdl' => ( ($this->useLegend) && (is_array($this->legend)) ) ? implode('|',$this->legend) : null, // Data legend
							'chs'   => $this->size[0].'x'.$this->size[1],						// Size
							'chco'   => preg_replace( '/[#]+/', '', implode(',',$this->color)), // Color ( Remove # from string )
							'chm'   => preg_replace( '/[#]+/', '', implode('|',$this->fill)),   // Fill ( Remove # from string )
							'chxt' => ( $this->labelsXY == true) ? 'x,y' : null,				// X & Y axis labels
							'chf' => preg_replace( '/[#]+/', '', $this->background),			// Background color ( Remove # from string )
						);

		// Return chart
		return 	$this->googlepie.http_build_query($this->query);
	}

	/** Set attributes
	*/
	function setChartAttrs( $attrs )
	{
		// debug
		$this->debug[] = $attrs;

		foreach( $attrs as $key => $value )
		{
			$this->{"set$key"}($value);
		}
		$this->display();
	}

	/** Set type
	*/
	function setType( $type )
	{
		$this->type = $type;
	}


	/** Set title
	*/
	function setTitle( $title )
	{
		$this->title = $title;
	}


	/** Set data
	*/
	function setData( $data )
	{
		// Clear any previous data
		unset( $this->data );

		// Check if multiple data
		if( is_array(reset($data)) )
		{
			/** Multiple sets of data
			*/
			foreach( $data as $key => $value )
			{
				// Add data values
				$this->data['values'][] = implode( ',', $value );

				// Add data names
				$this->data['names'] = implode( '|', array_keys( $value ) );
			}
			/** Implode data correctly
			*/
			$this->data['values'] = implode('|', $this->data['values']);
			/** Create legend
			*/
			$this->legend = array_keys( $data );
		}
		else
		{
			/** Single set of data
			*/
			// Add data values
			$this->data['values'] = implode( ',', $data );

			// Add data names
			$this->data['names'] = implode( '|', array_keys( $data ) );
		}

	}

	/** Set legend
	*/
	function setLegend( $legend )
	{
		$this->useLegend = $legend;
	}

	/** Set size
	*/
	function setSize( $width, $height = null )
	{
		// check if width contains multiple params
		if(is_array( $width ) )
		{
			$this->size = $width;
		}
		else
		{
			// set each individually
			$this->size[] = $width;
			$this->size[] = $height;
		}
	}

	/** Set color
	*/
	function setColor( $color )
	{
		$this->color = $color;
	}

	/** Set labels
	*/
	function setLabelsXY( $labels )
	{
		$this->labelsXY = $labels;
	}

	/** Set fill
	*/
	function setFill( $fill )
	{
		// Fill must have atleast 4 parameters
		if( count( $fill ) < 4 )
		{
			// Add remaining params
			$count = count( $fill );
			for( $i = 0; $i < $count; ++$i )
				$fill[$i] = 'b,'.$fill[$i].','.$i.','.($i+1).',0';
		}
		
		$this->fill = $fill;
	}


	/** Set background
	*/
	function setBackground( $background )
	{
		$this->background = 'bg,s,'.$background;
	}


}