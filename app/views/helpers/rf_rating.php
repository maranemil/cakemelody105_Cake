<?php
/**
 * $Id: rf_rating.php 33 2007-01-07 22:34:30Z thepaper $
 * ========================================================================
 * star rating helper - used with rf_rating component
 *
 * @author  Jack Pham (www.reversefolds.com)
 *          copyright (c) 2007
 *
 * License and Terms:
 * This software is provided AS-IS with no explicit or implied warranties 
 * or garantees whatsoever.  USE AT OWN RISK.  The original author,
 * hosting provider and anyone/everyone he knows is NOT LIABLE for any
 * damages incurred using, downloading or looking at this this 
 * software.
 *
 * Please send any bugfixes/modifications to jack@reversefolds.com
 * ========================================================================
 */
class RfRatingHelper extends Helper
{
    var $helpers = array('Ajax');

    /** base url(controller/action) to send to when voting */
    var $_voteUrl = '/ratings/vote/';

    /** list of 'hints' to display for rating */
    var $_hints = array('Easy', 'Okay', 'Some difficulty', 'Use the force', 'You must be kidding');

    /** show hint or not */
    var $_showHints = true;

    /**
     * display rating bar
     *
     * @param int id
     * @param array rating info
     * @param boolean text - print text of rating (2.5/5)
     */
    function ratingBar($info, $text = false) {

        $width = $info['unit_width'] * $info['units'];
        $liWidth = @number_format($info['rating']/$info['votes'], 2) * $info['unit_width'];

        // text representation of rating value
        $ratingText = $info['rating_value']. '/' . $info['units'];


        //$htmlString = '<div id="ratingblock">'."\n";
        $htmlString = $this->Ajax->div('ratingblock');

        // display current rating
        $htmlString .= '<div id="unit_long' . $info['id'] . '">';
	    $htmlString .= '<ul id="unit_ul' .  $info['id'] . '" class="unit-rating" style="width:' . $width . 'px;">';
        $htmlString .= '<li class="current-rating" style="width:' . $liWidth . 'px;">';
        $htmlString .= $ratingText;
        $htmlString .= '</li>';

        // draw voting stars if user has not voted yet
        if (!$info['voted']) {
            for ($i = 1; $i <= $info['units']; $i++) {

                $htmlString .= '<li>';
                $ajaxOptions = array('update' => 'ratingblock', 'class' => "r$i-unit rater");
               
                // add tooltip hints
                if ($this->_showHints) {
                    $hintIndex = $i - 1;
                    $ajaxOptions['title'] = $this->_hints[$hintIndex];
                }

                // add params to voting url
                $url = $this->_voteUrl . $i . '/' . $info['id'];

                $htmlString .= $this->Ajax->link($i, $url, $ajaxOptions);

                $htmlString .= '</span></li>';
            }
            $i =0;
        }

        $htmlString .= '</ul>';
        $htmlString .= '</div>';

        // show text representation of rating and votes
        $voteString = $info['votes'];
        if ($info['votes'] == 1) {
            $voteString .= ' vote';
        } else {
            $voteString .= ' votes';
        }

        $htmlString .= '<div id="ratingtxt">';
        if ($text) {
            $htmlString .= '<span id="ratetext">' . $ratingText . '</span>';
        }

        $htmlString .= ' <span id="totalvotes">' .  $voteString . '</span>';
        $htmlString .= '</div>';

        $htmlString .= $this->Ajax->divEnd('ratingblock');
 
        return $this->output($htmlString);

    }//ratingBar()

}//RfRatingHelper
?>
