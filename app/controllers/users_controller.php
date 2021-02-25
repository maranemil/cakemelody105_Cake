<?php

/**
 * users_controller.php, Provides Functions for User Authentification and Managment
 * Copyright (C) 2007  Christoph Hochstrasser
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Class UsersController
 */
class UsersController extends AppController {

   var $name       = 'Users';
   var $uses       = array('Video', 'User', 'Category', 'Rating');
   var $helpers    = array('Html', 'Javascript', 'Session', 'Head', 'Javascript', 'Ajax', 'Form', 'Pagination');
   var $components = array('Pagination', 'Upload', 'Email');

   /**
	* Display users  in list
	* @author: Christoph Hochstrasser
	*/

   function index() {
	  $criteria            = null;
	  $paging['sortBy']    = 'name';
	  $paging['direction'] = 'ASC';
	  $paging['show']      = '5';

	  list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
	  $arUser = $this->User->findAll($criteria, "", $order, $limit, $page);

	  if ($arUser) {
		 //$this->set(compact('comments','currentDateTime'));
		 $this->set("arUser", $arUser);
		 $this->pageTitle = ' Users ';
	  }
	  else {
		 //$this->redirect("/");
		 $this->flash('These is no info...', '/videos');
	  }
   }

   /**
	* @author: Christoph Hochstrasser
	*/
   function login() {
	  $this->pageTitle = 'Login';

	  // Sets the error variable to false
	  $this->set('error', 'false');

	  // If the form is submitted and form data is available, the form data is compared with the data in the database table.
	  if (!empty($this->data)) {
		 /* var_dump($this->data); die(); */

		 $sSQL    = "SELECT * FROM users WHERE users.username = '" . $this->data['User']['username'] . "' ";
		 $someone = $this->User->query($sSQL);

		 if (
			 !empty($someone[0]['users']['password']) &&
			 $someone[0]['users']['password'] == md5($this->data['User']['password']) &&
			 $someone[0]['users']['active'] == "true"
		 ) {
			//Sets the session variable with the user information
			$this->Session->write('User', $someone[0]['users']);

			//Sets the authentication variable
			$this->Session->write('authenticated', 'true');

			//Sets the rights variable, which contains the rights of the current user
			$this->Session->write('rights', $someone[0]['users']['group_id']);

			//Sets the status of the user to "online"
			$user           = $this->Session->read('User');
			$this->User->id = $user['id'];
			$this->User->saveField("online", "true");
			$this->flash('Succesfully Loged...', '/videos/');
		 }
		 else {
			// If the user cannot be authenticated, the error variable is set to "true"
			$this->set('error', 'true');
			$this->flash('Login process fail...', '/users/login');
		 }
	  }
   }

   /**
	* @author: Christoph Hochstrasser
	*/
   function register() {
	  $this->set('error', 'false');
	  $this->pageTitle = 'Registrierung';

	  // If the form is submitted and the two passwords match, the data will be processed
	  if (!empty($this->data) and $this->data['User']['password'] == $this->data['User']['password_confirm']) {
		 // Processing of the data
		 $form_data = array(
			 'User' => array(
				 'username' => $this->data['User']['username'],
				 'password' => md5($this->data['User']['password']),
				 'e-mail'   => $this->data['User']['e-mail'],
				 'name'     => $this->data['User']['name'],
				 'pastname' => $this->data['User']['pastname'],
				 'active'   => 'true' // if validation required by mail then make it false
			 )
		 );

		 // Writes the data into the Table "users"
		 if ($this->User->save($form_data)) {
			// Displays a Message on success
			$this->flash('' . $lbls["registred_ok"] . '' . $this->data['User']['username'] . '' . $lbls["registred_ok_sec"] . '', '/users/login');
		 }
	  }
	  elseif (!empty($this->data) and $this->data['User']['password'] != $this->data['User']['password_confirm']) {
		 // Otherwise the error variable is set to "true"
		 $this->set('error', 'true');
		 $this->flash('Register process fail...', '/users/login');
	  }
   }

   /**
	* @author: Christoph Hochstrasser
	*/
   function all() {
	  $this->checkIfLogged();
	  // Displays the Usernames of all Members including a link to their blog and user informations
	  $this->pageTitle = 'Alle Mitglieder';
	  $this->set('users', $this->User->findAll(null, array('id', 'username', 'online'), "username ASC"));
   }

   /**
	* @author: Christoph Hochstrasser
	*/
   function newest() {
	  //Displays the newest Members
	  $this->pageTitle = 'Neueste Mitglieder';
	  //$this->checkSession();
	  $this->set('users', $this->User->findAll(null, array('id', 'username', 'online', 'created'), 'created DESC', 10));
   }

   /**
	* @param null $id
	*/
   function view($id = null) {
	  if (!is_numeric($id)) $this->redirect('/');
	  $this->checkIfLogged();

	  //Displays the user information of the user with the supplied user id
	  $this->pageTitle = 'User view';

	  //$this->checkSession();
	  $this->User->id = $id;
	  $this->set('user', $this->User->read("id,username,e-mail,online,name,pastname,created,interests,userpage,nickname,image"));
   }

   /**
	* @param int|null $id
	*/
   function myprofile($id = null) {
	  if (!is_numeric($id)) $this->redirect('/');
	  $this->checkIfLogged();
	  $this->checkIfBelongsToArea($id);

	  //Displays the user information of the user with the supplied user id
	  $this->pageTitle = 'User profile';
	  //$this->checkSession();
	  $this->User->id = $id;
	  $this->set('user', $this->User->read("id,username,e-mail,online,name,pastname,created,interests,userpage,nickname"));
   }

   /**
	* Save user profile
	*
	* @param int $id
	*
	* @author         : maran_emil@yahoo.com
	* @web            : http://maran-emil.de
	* @web2            http://maran.pamil-visions.com
	* @license        http://www.opensource.org/licenses/mit-license.php The MIT License
	* @copyright      Copyright 2009, Maran Project.
	* @version        1.0
	*/
   function savemyprofile($id = null) {
	  if (!is_numeric($id)) $this->redirect('/');
	  $this->checkIfBelongsToArea($id);
	  $this->checkIfLogged();
	  //Displays the user information of the user with the supplied user id
	  $this->pageTitle = 'saving data...';
	  //$this->checkIfBelongsToArea($id);
	  $this->User->id           = $id;
	  $this->data["User"]["id"] = $id;
	  $output                   = date('Ymdhis') . ".jpg";

	  //print "<pre>"; print_r($_FILES); die();

	  /*Array
	  (
		  [data] => Array
			  (
				  [name] => Array ( [images] => Array ( [File] => unnamed4.jpg ) )
				  [type] => Array ( [images] => Array ( [File] => image/jpeg   ) )
				  [tmp_name] => Array ( [] => Array (   [File] => C:\xampp\tmp\phpC4.tmp  ) )
				  [error] =>Array ( [images] => Array ( [File] => 0 ) )
				  [size] => Array ( [images] => Array ( [File] => 7252 ) )
			  )
	  )*/

	  /*
	  echo APP."<br />"; // C:\xampp\htdocs\cakemelody\app\
	  echo APP_DIR."<br />"; // app
	  echo APP_PATH."<br />";
	  echo CAKE."<br />"; // cake\
	  echo COMPONENTS."<br />"; // C:\xampp\htdocs\cakemelody\app\controllers\components\
	  echo ROOT."<br />"; // C:\xampp\htdocs\cakemelody
	  echo WWW_ROOT."<br />"; // C:\xampp\htdocs\cakemelody\app\webroot\
	  echo WEBROOT_DIR."<br />"; // webroot
	  */

	  // $this->data["images"]["File"]["tmp_name"] = $_FILES["data"]["tmp_name"][0]["File"];

	  if ($this->data["images"]["File"]["tmp_name"]) {
		 $this->Upload->PbTempFile    = $this->data["images"]["File"]["tmp_name"];
		 $this->Upload->PbNewFileName = $output;

		 //$this->Upload->PbDestinationDirFile	= "../../".APP_DIR."/".WEBROOT_DIR."/img/user/".$output;
		 //$this->Upload->PbDestinationDir		= "../../".APP_DIR."/".WEBROOT_DIR."/img/user/";

		 $this->Upload->PbDestinationDirFile = WWW_ROOT . "/img/user/" . $output;
		 $this->Upload->PbDestinationDir     = WWW_ROOT . "/img/user/";

		 // $this->Upload->PbDestinationDirFile	= "../../app/webroot/img/user/".$output;
		 // $this->Upload->PbDestinationDir		= "../../app/webroot/img/user/";

		 if (!$this->Upload->uploadNewFile()) {
			$this->flash('Wrong data!', 'users/view/' . $id);
		 }
	  }

	  $arTmpform["User"]["id"]       = $this->data["User"]["id"];
	  $arTmpform["User"]["name"]     = $this->data["User"]["name"];
	  $arTmpform["User"]["pastname"] = $this->data["User"]["pastname"];
	  $arTmpform["User"]["nickname"] = $this->data["User"]["nickname"];
	  //$arTmpform["User"]["e-mail"]		= $this->data["User"]["e-mail"]; // don't change email
	  $arTmpform["User"]["interests"] = $this->data["User"]["interests"];

	  if ($this->data["images"]["File"]["tmp_name"]) {
		 $arTmpform["User"]["image"] = $output;
	  }

	  if ($this->Session->read("User.id") == $this->User->id) {
		 //Displays a Message on success
		 //print "<pre>"; print_r($this->data); die();
		 $this->User->save($arTmpform);
		 $this->flash('Profile saved...', '/users/view/' . $this->User->id);
	  }
   }

   /**
	* Save user profile old function
	*
	* @param int $id
	*
	* @author         : maran_emil@yahoo.com
	* @web            : http://maran-emil.de
	* @web2            http://maran.pamil-visions.com
	* @license        http://www.opensource.org/licenses/mit-license.php The MIT License
	* @copyright      Copyright 2009, Maran Project.
	* @version        1.0
	*/
   function savemyprofileold($id = null) {
	  if (!is_numeric($id)) $this->redirect('/');
	  //Displays the user information of the user with the supplied user id
	  $this->pageTitle = 'saving data...';
	  $this->checkIfLogged();
	  $this->User->id = $id;

	  if ($this->data["images"]["File"]["tmp_name"]) {
		 $output                             = date('Ymdhis') . ".jpg";
		 $this->Upload->PbTempFile           = $this->data["images"]["File"]["tmp_name"];
		 $this->Upload->PbDestinationDirFile = "../../app/webroot/img/user/" . $output;
		 $this->Upload->PbDestinationDir     = "../../app/webroot/img/user/";
		 $this->Upload->PbNewFileName        = $output;

		 if (!$this->Upload->uploadNewFile()) {
			$this->flash('Wrong data!', 'users/');
		 }

		 $this->data["User"]["image"] = $output;
	  }

	  if ($this->Session->read("User.id") == $this->User->id) {
		 //Displays a Message on success
		 $this->User->save($this->data);
		 $this->flash('Profile saved...', '/users/view/' . $this->User->id);
	  }
	  // $this->set('user', $this->User->read("id,username,e-mail,online,name,pastname,created,interests,userpage,nickname"));

   }

   /**
	*
	*/
   function logout() {
	  //Destroys all session variables and sets the status of the user to "offline"
	  $this->pageTitle = 'Abmeldung';

	  //Reads the user id out of the session variable
	  $user           = $this->Session->read('User');
	  $this->User->id = $user['id'];

	  //Sets the status to "offline"
	  $this->User->saveField("online", "false");

	  //Destroys all session variables
	  $this->Session->delete('User');
	  $this->Session->delete('authenticated');
	  $this->Session->delete('rights');

	  //Displays a success message and redirects to the Homepage for not registered users
	  $this->flash('Logout sucesfully! ...', '/');
	  //$this->redirect('/videos');
   }

   /**
	* @param int|null $id
	*
	* @return false|mixed|string
	*/
   function getuserbyid($id = null) {
	  if (!is_numeric($id)) $this->redirect('/');
	  if (isset($this->params['requested']) and $this->params['requested']) {
		 $arUsr = $this->User->query("SELECT nickname,name FROM users where id=" . $id . " LIMIT 1");
		 if ($arUsr[0]["users"]['nickname']) {
			$nickname = $arUsr[0]["users"]['nickname'];
		 }
		 else {
			$nickname = "music lover";
		 }
		 return $nickname;
	  }
	  else {
		 return false;
	  }
   }

   /**
	* @param int $limit
	*
	* @return false
	*/
   function lastusers($limit = 8) {
	  if (!is_numeric($limit)) $this->redirect('/');
	  $criteria = null;
	  $criteria = array("User.image LIKE " => "%%");

	  //$order = ' ORDER by User.id DESC ';
	  $order = ' ORDER BY Rand() ';
	  if (isset($this->params['requested']) and $this->params['requested']) {
		 //return 	$this->Company->find('all', array('order' => 'Company.id DESC', 'limit' => $limit));
		 return $this->User->findAll($criteria, "", $order, $limit, $page);
	  }
	  else {
		 return false;
	  }
   }

   /**
	*
	*/
   function forgotpassword() {
	  $this->layout = "default";

	  if ($this->data) {
		 $criteria = null;
		 $criteria = array("User.username" => $this->data["User"]["username"]);
		 $uDetails = $this->User->findAll($criteria, "", $order, $limit, $page);

		 if ($uDetails && $this->data["User"]["username"] == $uDetails[0]["User"]["username"]) {
			$newToken = $this->create_password(20);
			$to       = $this->data["User"]["username"];
			$nameto   = "Portal User";
			$from     = "info@youremailhere.com";
			$namefrom = "Your Website Name";
			$subject  = "Recovering Password Service";

			$message = "To change your password follow the next link:  - <A HREF='http://" . $this->webroot . "/users/forgotpasswordtoken/" . $newToken . "'> http://publion.ro/users/forgotpasswordtoken/" . $newToken . " </A>";

			$this->User->query("UPDATE users SET token='" . $newToken . "' WHERE username='" . $uDetails[0]["User"]["username"] . "' ");

			$this->Email->authSendEmail($from, $namefrom, $to, $nameto, $subject, $message);
			$this->flash('In short time you will get an email! ...', '/');
		 }
		 else {
			$this->set('error', 1);
			$this->flash('There was some error! ...', '/');
		 }
	  }
   }

   /**
	*
	*/
   function forgotpasswordtoken() {
	  $sToken = $this->params["pass"][0];

	  if ($this->data) {
		 $this->layout = "default";

		 //print "<pre>";   print_r($this->data); print "</pre>"; die();
		 if ($this->data["User"]["newpassword1"] == $this->data["User"]["newpassword2"]) {
			$sNewPass = md5($this->data["User"]["newpassword1"]);
			$this->User->query("UPDATE users SET password='" . $sNewPass . "',token='' WHERE token='" . $this->data["User"]["token"] . "' ");
			$this->flash('The password has been changed...', '/');
		 }
		 else {
			//$this->flash('Password not corect. ', '/');
			$this->flash('Incorect password! ...', '/');
		 }
	  }
	  else if ($sToken) {
		 $criteria = null;
		 $criteria = array("User.token" => $sToken);
		 $uDetails = $this->User->findAll($criteria, "", $order, $limit, $page);

		 //print "<pre>";   print_r($sToken); print "</pre>";
		 //print "<pre>";   print_r($uDetails); print "</pre>"; die();

		 if ($uDetails[0]["User"]["token"] == $sToken) {
			//$this->flash('Introdu o noua parola...', '/');
			$this->set("token", $sToken);
		 }
		 else {
			//$this->flash('Parola sau datele sunt incorecte. Nu se pot schimba.', '/');
			$this->flash('Incorect password! Impossible to change ...', '/');
		 }
	  }
   }

   /**
	*
	*/
   function changepassword() {
	  $this->checkIfLogged();
	  $this->layout = "default";

	  if ($this->data) {
		 /*
				 print "<pre>";   print_r($this->data); print "</pre>";
				 print "<pre>";   print_r($this->Session); print "</pre>";
				 print "<pre>";   print_r($uDetails); print "</pre>"; die();
		 */

		 $criteria = null;
		 $criteria = array("User.id" => $this->Session->read("User.id"));
		 $uDetails = $this->User->findAll($criteria, "", $order, $limit, $page);

		 if ($uDetails && $this->Session->read("User.id") == $uDetails[0]["User"]["id"]) {
			if (
				(md5($this->data["User"]["oldpassword"]) == $uDetails[0]["User"]["password"]) &&
				($this->data["User"]["newpassword1"] == $this->data["User"]["newpassword2"])
			) {
			   $sNewPass = md5($this->data["User"]["newpassword1"]);
			   $this->User->query("UPDATE users SET password='" . $sNewPass . "' WHERE id='" . $this->Session->read("User.id") . "' ");
			   $this->flash('Password has been changed...', '/');
			}
			else {
			   $this->flash('Password cannot be changed.', '/users/changepassword');
			   //$this->flash('Incorect password! Impossible to change ...', '/');
			}
		 }
		 else {
			$this->set('error', 1);
		 }
	  }
   }

   /*----------------------------------------------------------
   / Generate a random password
   / http://www.webtoolkit.info/php-random-password-generator.html
   / http://pastebin.com/YNQkEwbs
   ----------------------------------------------------------*/

   /**
	* @param int $length
	* @param int $strength
	*
	* @return string
	*/
   function generatePassword($length = 9, $strength = 0) {
	  $vowels     = 'aeuy';
	  $consonants = 'bdghjmnpqrstvz';
	  if ($strength & 1) {
		 $consonants .= 'BDGHJLMNPQRSTVWXZ';
	  }
	  if ($strength & 2) {
		 $vowels .= "AEUY";
	  }
	  if ($strength & 4) {
		 $consonants .= '23456789';
	  }
	  if ($strength & 8) {
		 $consonants .= '@#$%';
	  }

	  $password = '';
	  $alt      = time() % 2;
	  for ($i = 0; $i < $length; $i++) {
		 if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt      = 0;
		 }
		 else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt      = 1;
		 }
	  }
	  return $password;
   }

   /*----------------------------------------------------------
   / Generate a random password2
   / http://archiv.raid-rush.ws/t-673811.html
   / http://www.inside-php.de/scripte/PHP-Code%20Ausschnitte-18/Erweiterter-Passwort-Generator.html
   ----------------------------------------------------------*/

   /**
	* @param int $length
	*
	* @return false|string
	*/
   function generatePW($length = 8) {
	  $dummy = array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'), array('#', '&', '@', '$', '_', '%', '?', '+'));

	  // shuffle array

	  mt_srand((double)microtime() * 1000000);

	  for ($i = 1; $i <= (count($dummy) * 2); $i++) {
		 $swap         = mt_rand(0, count($dummy) - 1);
		 $tmp          = $dummy[$swap];
		 $dummy[$swap] = $dummy[0];
		 $dummy[0]     = $tmp;
	  }

	  // get password

	  return substr(implode('', $dummy), 0, $length);
	  # echo generatePW(10); // 10stelliges Passwort ausgeben...
   }

   /*----------------------------------------------------------
   / Generate a random password3
   / http://snipplr.com/view/15402/
   / http://snipt.net/azote/random-password-generator-1/
   / http://pastebin.com/hNhQ79pK
   ----------------------------------------------------------*/

   /**
	* @param int    $length
	* @param int    $use_upper
	* @param int    $use_lower
	* @param int    $use_number
	* @param string $use_custom
	*
	* @return string
	*/
   function create_password($length = 8, $use_upper = 1, $use_lower = 1, $use_number = 1, $use_custom = "") {
	  $upper       = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	  $lower       = "abcdefghijklmnopqrstuvwxyz";
	  $number      = "0123456789";
	  $seed_length = null;
	  $seed        = null;

	  if ($use_upper) {
		 $seed_length += 26;
		 $seed        .= $upper;
	  }

	  if ($use_lower) {
		 $seed_length += 26;
		 $seed        .= $lower;
	  }

	  if ($use_number) {
		 $seed_length += 10;
		 $seed        .= $number;
	  }

	  if ($use_custom) {
		 $seed_length += strlen($use_custom);
		 $seed        .= $use_custom;
	  }

	  for ($x = 1; $x <= $length; $x++) {
		 $password .= $seed{rand(0, $seed_length - 1)};
	  }

	  return ($password);

	  //USAGE
	  /*
	  echo create_password(); // Returns for example a7YmTwG4
	  echo create_password(16); // Returns for example Z77OzzS3DgV3OxxP
	  echo create_password(8,0,0); // Returns for example 40714215
	  echo create_password(10,1,1,1,";,:.-_()"); // Returns for example or)ZA10kpX
	  */
   }

} // end class
