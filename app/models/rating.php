<?php

/**
 * Rating Model.
 *
 * @author:        maran_emil@yahoo.com
 * @web:            http://maran-emil.de
 * @web2            http://maran.pamil-visions.com
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright    Copyright 2009, Maran Project.
 * @version        1.0
 */

class Rating extends AppModel
{

    var $name = 'Rating';
    var $belongsTo = 'Videos';

    /*
        var $validate = array(
            'title' => array(
                'rule'		=> array('minLength', 1),
                'required'	=> TRUE
            )
        );
    */


}


