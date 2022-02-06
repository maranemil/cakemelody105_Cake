<?php /** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnused */

# Chart
# by Ludwig Pettersson
# <http://luddep.se>
# With help from Fredrik Holmstr�m
# <http://loveandtheft.org/>
# Copyright (c) 2008 Ludwig Pettersson

class ChartHelper extends AppHelper
{
    public $helpers = array('Html');
    // Constants
    public $googlepie = 'https://chart.apis.google.com/chart?';

    // Variables
    public $types
        = array(
            'pie'            => 'p',
            'pie3d'          => 'p3',
            'line'           => 'lc',
            'sparkline'      => 'ls',
            'bar-horizontal' => 'bhg',
            'bar-vertical'   => 'bvg',
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
    public $color
        = array(
            '#555555',
            '#FF0000',
            '#EA0D07',
            '#FFFF48',
            '#872D2D'
        );

    public $type;
    public $title;
    public $data       = array();
    public $size       = array();
    public $fill       = array();
    public $labelsXY   = false;
    public $legend;
    public $useLegend  = false;
    public $background = 'a,s,ffffff';

    public $query = array();

    // debug
    public $debug = array();

    // Return string
    public function __toString()
    {
        return $this->display();
    }

    /** Create chart
     */
    public function display()
    {
        // Create query
        $this->query = array(
            'cht'  => $this->types[strtolower($this->type)],                    // Type
            'chtt' => $this->title,                                            // Title
            'chd'  => 't:' . $this->data['values'],                                // Data
            'chl'  => $this->data['names'],                                    // Data labels
            'chdl' => (($this->useLegend) && (is_array($this->legend))) ? implode('|', $this->legend) : null, // Data legend
            'chs'  => $this->size[0] . 'x' . $this->size[1],                        // Size
            'chco' => preg_replace('/[#]+/', '', implode(',', $this->color)), // Color ( Remove # from string )
            'chm'  => preg_replace('/[#]+/', '', implode('|', $this->fill)),   // Fill ( Remove # from string )
            'chxt' => ($this->labelsXY === true) ? 'x,y' : null,                // X & Y axis labels
            'chf'  => preg_replace('/[#]+/', '', $this->background),            // Background color ( Remove # from string )
        );

        // Return chart
        return $this->googlepie . http_build_query($this->query);
    }

    /**
     * Set attributes
     *
     * @param $attrs
     */
    public function setChartAttrs($attrs)
    {
        // debug
        $this->debug[] = $attrs;

        foreach ($attrs as $key => $value) {
            $this->{"set$key"}($value);
        }
        $this->display();
    }

    /**
     * Set type
     *
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Set title
     *
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set data
     *
     * @param $data
     */
    public function setData($data)
    {
        // Clear any previous data
        unset($this->data);

        // Check if multiple data
        if (is_array(reset($data))) {
            /** Multiple sets of data
             */
            foreach ($data as $value) {
                // Add data values
                $this->data['values'][] = implode(',', $value);

                // Add data names
                $this->data['names'] = implode('|', array_keys($value));
            }
            /** Implode data correctly
             */
            $this->data['values'] = implode('|', $this->data['values']);
            /** Create legend
             */
            $this->legend = array_keys($data);
        } else {
            /** Single set of data
             */
            // Add data values
            $this->data['values'] = implode(',', $data);

            // Add data names
            $this->data['names'] = implode('|', array_keys($data));
        }
    }

    /**
     * Set legend
     *
     * @param $legend
     */
    public function setLegend($legend)
    {
        $this->useLegend = $legend;
    }

    /**
     * Set size
     *
     * @param int $width
     * @param int $height
     */
    public function setSize($width, $height = null)
    {
        // check if width contains multiple params
        if (!empty($width)) {
            $this->size = $width;
        } else {
            // set each individually
            $this->size[] = $width;
            $this->size[] = $height;
        }
    }

    /**
     * Set color
     *
     * @param $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Set labels
     *
     * @param $labels
     */
    public function setLabelsXY($labels)
    {
        $this->labelsXY = $labels;
    }

    /**
     * Set fill
     *
     * @param $fill
     */
    public function setFill($fill)
    {
        // Fill must have atleast 4 parameters
        if (count($fill) < 4) {
            // Add remaining params
            foreach ($fill as $i => $iValue) {
                $fill[$i] = 'b,' . $iValue . ',' . $i . ',' . ($i + 1) . ',0';
            }
        }

        $this->fill = $fill;
    }

    /**
     * Set background
     *
     * @param $background
     */
    public function setBackground($background)
    {
        $this->background = 'bg,s,' . $background;
    }

}