<?php
	/**
	* Controller Comments
	*
	* @author		Maran Emil | Maran Project | maran_emil@yahoo.com
	* @copyright	Copyright 2009, Maran Project.
	* @link		http://maran-emil.de 
	* @version		1.0
	* @license		http://www.opensource.org/licenses/mit-license.php The MIT License
	*/

	class CommentsController extends AppController
	{
		/**
		 * No....
		 *
		 * @var string
		 */

		var $name = "Comments";
		
		/**
		 * Helpers 
		 *
		 * @var array
		 */
		
		var $uses = array('Video','User','Category','Comment');
		var $helpers = array('Html', 'Javascript', 'Session','Head','Javascript', 'Ajax','Form','Pagination'); 
		var $components = array('Pagination','Upload'); 
		
		function index()
		{

		//	print_r($this->Session ->read("User"));
		}

		function view($id = NULL) {
			$this->Video->id = $id;			
			
		}

		function checkIfLogged(){
		//	$this->checkSession();
			if(!$this->Session->read("User")){
				$this->flash('Please login or register first...', '/');
				$this->redirect(PATHWEB."/users/login"); 
				exit;
			}
		}

		function step1($id = NULL) {
			
		}


		function step2() {

		}


		function showcategory($id){
			
		}


	/*
		public function list_latest($limit = 5)	
		{
			if ( isset($this->params['requested']) AND $this->params['requested'] )
			{
				return 	$this->Company->find('all', array('order' => 'Company.id DESC', 'limit' => $limit));
				}
			else
			{
				return FALSE;
			}
		}
	*/

		public function searchcomment($searchq = NULL) 
		{



		}



	}

?>
