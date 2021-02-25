<?php
/* infos_controller.php, Provides Functions for Static Pages
    Copyright (C) 2007  Christoph Hochstrasser

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    */

/**
 * Controller Infos
 * @author         :        maran_emil@yahoo.com
 * @web            :            http://maran-emil.de
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 * @copyright      Copyright 2009, Maran Project.
 * @version        1.0
 */
class InfosController extends AppController {

   var $name      = 'Infos';
   var $pageTitle = "Infos";
   var $layout    = 'default';
   var $uses      = array("Info");
   var $helpers   = array('Html', 'Javascript', 'Session', 'Head', 'Javascript', 'Ajax', 'Form', 'Pagination', 'Chart');

   /*----------------------------------------------------------
   / index
   / @author: Maran Emil
   ----------------------------------------------------------*/

   function index() {
   }

   /*----------------------------------------------------------
   / contact us
   / @author: Maran Emil
   ----------------------------------------------------------*/

   function contactus() {
	  $this->pageTitle = ' - Contact Us';
   }

   /*----------------------------------------------------------
   / what is this
   / @author: Maran Emil
   ----------------------------------------------------------*/

   function whatisthis() {
	  $this->pageTitle = ' - WTF is this?';
   }

   function cakephpapps() {
	  $this->pageTitle = ' - Resources';
   }

}

