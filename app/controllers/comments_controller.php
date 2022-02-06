<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUndefinedConstantInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUnused */

/**
 * Controller Comments
 * @property $Video
 * @property $Session
 * @author         Maran Emil | Maran Project | maran_emil@yahoo.com
 * @copyright      Copyright 2009, Maran Project.
 * @link           http://maran-emil.de
 * @version        1.0
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CommentsController extends AppController
{
    /**
     * No....
     * @var string
     */

    public $name = "Comments";

    /**
     * Helpers
     * @var array
     */

    public $uses       = array('Video', 'User', 'Category', 'Comment');
    public $helpers    = array('Html', 'Javascript', 'Session', 'Head', 'Javascript', 'Ajax', 'Form', 'Pagination');
    public $components = array('Pagination', 'Upload');

    public function index()
    {
        //	print_r($this->Session ->read("User"));
    }

    public function view($id = null)
    {
        $this->Video->id = $id;
    }

    public function checkIfLogged()
    {
        //	$this->checkSession();
        if (!$this->Session->read("User")) {
            $this->flash('Please login or register first...', '/');
            $this->redirect(PATHWEB . "/users/login");
            exit;
        }
    }

    public function step1($id = null)
    {
    }

    public function step2()
    {
    }

    public function showcategory($id)
    {
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

    public function searchcomment($searchq = null)
    {
    }

}


