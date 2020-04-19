<?php
/**
 * Video Model.
 *
 * @author:        maran_emil@yahoo.com
 * @web:            http://maran-emil.de
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright    Copyright 2009, Maran Project.
 * @version        1.0
 */

class Video extends AppModel
{
    var $name = 'Video';
    var $validate = array(
        'bandname' => VALID_NOT_EMPTY,
        'songtitle' => VALID_NOT_EMPTY,
        'tubecode' => VALID_NOT_EMPTY,
        'id' => VALID_NUMBER,
        'category_id' => VALID_NUMBER,
        'user_id' => VALID_NUMBER
    );

    /*
    # VALID_NOT_EMPTY
    # VALID_EMAIL
    # VALID_NUMBER
    # VALID_YEAR

    http://bakery.cakephp.org/articles/view/multiple-rules-of-validation-per-field-in-cakephp-1-2
    http://cakebaker.42dh.com/2007/01/03/validation-with-cakephp-12/
    http://snook.ca/archives/cakephp/cakephp_data_va_1
    */


    /*
     public $useTable = "guestbook_entries";

       public function listAll(){
          return $this->find("all",array("order"=>array("Entry.created desc")));
       }
    */

}//

