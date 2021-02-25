<?php
/* user.php, Provides Database Functionality for the table "users"
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
    along with this program.  If not, see <http://www.gnu.org/licenses/>.*/

class User extends AppModel {

   var $name = 'User';

   var $validate = array(
	   'username' => VALID_NOT_EMPTY,
	   'password' => VALID_NOT_EMPTY,
	   'e-mail'   => VALID_EMAIL,
	   'id'       => VALID_NUMBER

   );

   var $hasOne = array('Videos' =>
						   array('className'  => 'Video',
								 'foreignKey' => 'user_id',
								 'conditions' => '',
								 'fields'     => '',
								 'order'      => '',
								 'dependent'  => ''
						   )
   );

}

// http://justkez.com/understanding-cakephp-sessions/
// http://api.cakephp.org/view_source/session-helper/
// http://blog.dievolution.net/cakephp/kurztipp-sessions-in-cakephp/
