<?php /** @noinspection DuplicatedCode */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpArrayIndexImmediatelyRewrittenInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection SqlNoDataSourceInspection */
/** @noinspection SqlDialectInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUnused */
/** @noinspection PhpDynamicAsStaticMethodCallInspection */
/** @noinspection PhpUndefinedVariableInspection */
/** @noinspection SuspiciousAssignmentsInspection */

/**
 * Controller Videos
 * [Short Description]
 * @package       Videos Plugin
 * @author        Emil Maran <maran.emil@gmail.com>
 * @link          http://www.maran-emil.de
 * @copyright (c) 2013 Emil Maran
 * @license       MIT License - http://www.opensource.org/licenses/mit-license.php
 **/

App::import('Sanitize');

/**
 * Class VideosController
 */
class VideosController extends AppController
{

    public $name       = "Videos";
    public $uses       = array('Video', 'User', 'Category', 'Rating');
    public $helpers    = array('Html', 'Javascript', 'Session', 'Head', 'Javascript', 'Ajax', 'Form', 'Pagination', 'Chart');
    public $components = array('Pagination', 'Upload');

    public $pageSortBy = "id";
    public $pageSortDi = "DESC";
    public $pageShowPg = "15";
    #public $Session     = array();

    const PageVideosTitle = 'Cakemelody - Videos';

    /**
     * get all gategories in ASC oder
     *
     * @param int $id
     *
     * @return array
     */
    public function getCategories($id = null)
    {
        if ($id) {
            $sSQL = 'SELECT * FROM categories WHERE categories.id = $id ORDER BY categories.name';
        } else {
            $sSQL = 'SELECT * FROM categories ORDER BY categories.name';
        }
        return $this->Category->query($sSQL);
    }

    /**
     * Index video
     *
     *
     * @return void
     */
    public function index()
    {
        //$criteria = null;

        $criteria            = " Video.recom = '1' ";
        $paging['sortBy']    = $this->pageSortBy;
        $paging['direction'] = $this->pageSortDi;
        $paging['show']      = $this->pageShowPg;

        list($limit, $page) = $this->Pagination->init($criteria, $paging);

        #$arTmpVid	= $this->Video->findAll($criteria,"", $order, $limit, $page);

        // prepare selection parameters
        $arTmpVid = $this->Video->find(
            'all',
            array(
                'order'      => 'Video.id DESC',
                'limit'      => $limit,
                'page'       => $page,
                'conditions' => array("Video.recom = '1'")
            )
        );

        $arTmpCats = $this->getCategories();

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = self::PageVideosTitle;
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
    }

    /**
     * Videos by Category
     *
     * @param int $id
     *
     * @return void
     */
    public function category($id = null)
    {
        if (!is_numeric($id)) {
            $this->redirect('/');
        }

        $id = $this->params["pass"][0];
        if (!$id) {
            $id = 1;
        }

        $criteria = " Video.category_id=" . $id . " ";

        $paging['sortBy']    = $this->pageSortBy;
        $paging['direction'] = $this->pageSortDi;
        $paging['show']      = $this->pageShowPg;

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid = $this->Video->findAll($criteria, "", $order, $limit, $page);

        $arTmpCats = $this->getCategories();

        if (isset($arTmpVid)) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = self::PageVideosTitle;
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
    }

    /**
     * page Videos by User ID
     *
     * @param int|string $id
     *
     * @return void
     */
    public function uservideos($id = null)
    {
        if (!is_numeric($id)) {
            $this->redirect('/');
        }
        if ($id === null) {
            $id = 1;
        }

        $criteria = " Video.user_id=" . $id;

        $paging['sortBy']    = $this->pageSortBy;
        $paging['sortBy']    = "views";
        $paging['direction'] = $this->pageSortDi;
        $paging['show']      = $this->pageShowPg;

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);

        $arTmpVid  = $this->Video->findAll($criteria, "", $order, $limit, $page);
        $arTmpCats = $this->getCategories();

        if (isset($arTmpVid)) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = self::PageVideosTitle;
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
        //	print_r($this->Session ->read("User"));
    }

    /**
     * page Display Videos - alternative function for Index
     *
     *
     * @return void
     */
    public function display()
    {
        //$criteria			= null;
        $criteria = " Video.recom = 1 ";

        $paging['sortBy']    = $this->pageSortBy;
        $paging['sortBy']    = "views";
        $paging['direction'] = $this->pageSortDi;
        $paging['show']      = $this->pageShowPg;

        list(, $limit, $page) = $this->Pagination->init($criteria, $paging);

        /*

        $arTmpVid = $this->Video->findAll(
            $criteria,
            "",
            $order,
            $limit,
            $page
        );

        */

        $arTmpVid = $this->Video->find(
            'all', array(
                     'order'      => 'Video.id DESC',
                     'limit'      => $limit,
                     'page'       => $page,
                     'conditions' => array("Video.recom = '1'")
                 )
        );

        $arTmpCats = $this->getCategories();

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = self::PageVideosTitle;
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
    }

    /**
     * page Display Top Videos
     *
     *
     * @return void
     */
    public function topvideos()
    {
        $criteria = null;
        //$criteria = 'conditions' => array("Video.removed = '0'");

        $paging['sortBy']    = $this->pageSortBy;
        $paging['sortBy']    = "views";
        $paging['direction'] = $this->pageSortDi;
        $paging['show']      = $this->pageShowPg;

        list(, $limit, $page) = $this->Pagination->init($criteria, $paging);

        //$arTmpVid = $this->Video->findAll($criteria,"", $order, $limit, $page);
        $arTmpVid = $this->Video->find(
            'all', array(
                     'order'      => 'Video.views DESC',
                     'limit'      => $limit,
                     'page'       => $page,
                     'conditions' => array("Video.removed = '0'")
                 )
        );

        $arTmpCats = $this->getCategories();

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = self::PageVideosTitle;
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
        //	print_r($this->Session ->read("User"));
    }

    /**
     * page Display New Videos
     *
     *
     * @return void
     */
    public function newvideos()
    {
        $criteria = null;

        $paging['sortBy']    = $this->pageSortBy;
        $paging['sortBy']    = "date";
        $paging['direction'] = $this->pageSortDi;
        $paging['show']      = $this->pageShowPg;

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid = $this->Video->findAll($criteria, "", $order, $limit, $page);

        $arTmpCats = $this->getCategories();

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = self::PageVideosTitle;
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
    }

    /**
     * page Display Recommended Videos
     *
     *
     * @return void
     */
    public function recommended()
    {
        //$criteria = null;
        $paging['sortBy']    = $this->pageSortBy;
        $paging['sortBy']    = "viewdate";
        $paging['direction'] = $this->pageSortDi;
        $paging['show']      = $this->pageShowPg;

        //$order = ' ORDER BY Rand() ';
        $criteria = array("Video.recom" => "1");

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $this->Video->recursive = 0;
        $arTmpVid               = $this->Video->findAll($criteria, null, $order, $limit, $page);

        $arTmpCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name ASC');

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = self::PageVideosTitle;
        } else {
            $this->redirect('/videos/index');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
    }

    /**
     * page View  Video
     *
     * @param int $id
     *
     * @return void
     */
    public function view($id = null)
    {
        if (!is_numeric($id)) {
            $this->redirect('/');
        }
        $this->Video->id = $id;

        // update views
        $ratingSQL = "SELECT SUM(rateval) as Gesamt, COUNT(rateval) as Votes FROM ratings WHERE video_id=" . $id . " ";
        $ratingTMP = $this->Rating->query($ratingSQL);

        $arTmp     = $this->Video->query('SELECT * FROM videos WHERE id=' . $id);
        $viewsplus = $arTmp[0]['videos']['views'] + 1;

        $this->Video->query("UPDATE videos SET views = '" . $viewsplus . "' WHERE id=" . $id);
        $this->Video->query("UPDATE videos SET viewdate = '" . date("Y-m-d") . "' WHERE id=" . $id);

        //if(!isset($ratingTMP[0][0]['Gesamt'])) $ratingTMP[0][0]['Gesamt'] = 1;
        //if(!isset($ratingTMP[0][0]['Votes'])) $ratingTMP[0][0]['Votes'] = 1;

        $ratingVal = round($ratingTMP[0][0]['Gesamt'] / $ratingTMP[0][0]['Votes']);
        //if(!$ratingVal) $ratingVal = 1;

        $arTmpV    = $this->Video->query('SELECT * FROM videos WHERE id=' . $id);
        $viewsplus = $arTmpV[0]['videos']['views'] + 1;

        $this->Video->query("UPDATE videos SET views = '" . $viewsplus . "' WHERE id=" . $id);

        //$comm =  $this->Comment->query('SELECT * FROM comments WHERE id='.$id);

        $this->set("Video", $arTmpV);
        $this->set("rating", $ratingVal);
        $this->set("votes", $ratingTMP[0][0]['Votes']);
        //$this->set("comm", $comm);
    }

    /**
     * page Rating Save Videos
     *
     *
     */
    public function ratingsave()
    {
        // get special ajax template
        $this->layout = "ajax";

        $rating_val = $this->params["pass"][0];
        $article_id = $this->params["pass"][1];
        $userid    = $this->Session->read("User.id");

        //if($userid) $userid = 1;

        $tmpdata = array(
            "Rating" => array(
                "video_id" => $article_id,
                "rateval"  => $rating_val,
                "user_id"  => $userid,
                "user_ip"  => $_SERVER['REMOTE_ADDR']
            )
        );

        $ratingSQL = "SELECT * FROM ratings WHERE user_ip='" . $_SERVER['REMOTE_ADDR'] . "' AND video_id='" . $article_id . "'";
        $chekcRate = $this->Rating->query($ratingSQL);
        if (!$chekcRate) {
            $this->Rating->save($tmpdata);
        }
    }

    /**
     * page Step1 add new video
     *
     * @param int $id
     *
     * @return void
     */
    public function step1($id = null)
    {
        if (!is_numeric($id)) {
            $this->redirect('/');
        }

        $this->checkIfLogged();
        $this->pageTitle = 'Add Video...';

        $arTmpCat = $this->getCategories();

        //print "<pre>"; print_r($arTmpCat);	print "</pre>";// die();
        $this->set("arTmpCat", $arTmpCat);
    }

    /**
     * page Step2 add new video - Save
     * @return bool
     */
    public function step2()
    {
        $this->checkIfLogged();

        //print "<pre>"; print_r($this->data);	print "</pre>"; die();
        $this->pageTitle = 'saving data...';

        // get YouTube code from url or simple code
        if (strpos($this->data['Video']['tubecode'], "http") !== false) {

            //$str1="http://www.youtube.com/watch?v=0bREy8pQX3U";
            //$str2 ="0bREy8pQX3U";

            $arCodeTmp     = explode("?v=", trim($this->data['Video']['tubecode']));
            $newVideoCode  = trim($arCodeTmp[1]);
            $arCodeTmpSec  = explode("&", trim($newVideoCode));
            $newVideoCodeF = trim($arCodeTmpSec[0]);

            if ($newVideoCodeF && strlen($newVideoCodeF) === 11) {
                $Videotubecode = $newVideoCodeF;
            } else {
                $this->flash('Wrong Video Tube code!', '/videos/step1/');
            }
        } else if (strlen(trim($this->data['Video']['tubecode'])) === 11) {
            $Videotubecode = trim($this->data['Video']['tubecode']);
        } else {
            $this->flash('Wrong Video Tube code!', '/videos/step1/');
            //$this->data['Video']['tubecode'] = "onBxK4y9s_U"; // default?
        }

        if ($this->data['Video']["category_id"]) {
            $form_data = array(
                'Video' => array(
                    'category_id' => $this->data['Video']["category_id"],
                    'bandname'    => $this->data['Video']['bandname'],
                    'songtitle'   => $this->data['Video']['songtitle'],
                    'tubecode'    => $Videotubecode,
                    'tags'        => $this->data['Video']['tags'],
                    'date'        => date("Y-m-d"),
                    'ip1'         => $REMOTE_ADDR,
                    'ip2'         => $_SERVER['REMOTE_ADDR'],
                    'user_id'     => $this->Session->read("User.id")
                )
            );

            if ($this->Video->save($form_data)) {
                //Displays a Message on success
                //$this->flash(''.$lbls["registred_ok"].'Your video has been saved succesfully', '/videos');
                $this->redirect('/videos');
            }
        } else {
            $this->flash('Wrong data!','/videos/step1/');
        }
        #####################################################################################
        //print "<pre>"; print_r($this->data);	print "</pre>";// die();
        return false;

    }

    /**
     * Show category name
     *
     * @param int $id
     *
     * @return array
     */
    public function showcategory($id)
    {
        if (!is_numeric($id)) {
            $this->redirect('/');
        }

        $currCat = "Default";

        $categorySQL     = 'SELECT * FROM categories ORDER BY categories.name ASC';
        $arTmpCatSubCats = $this->Category->query($categorySQL);

        foreach ($arTmpCatSubCats as $sCat) {
            if ($sCat['categories']["id"] === $id) {
                $currCat = $sCat['categories']["name"];
            }
        }
        return $currCat;
    }

    /**
     * Get last videos
     *
     * @param int|string $limit
     *
     * @return bool
     */
    public function list_latest($limit = 9)
    {
        if (!is_numeric($limit)) {
            $this->redirect('/');
        }
        if (isset($this->params['requested']) && $this->params['requested']) {
            return $this->Video->find('all', array('order' => 'Video.id DESC', 'limit' => $limit, 'conditions' => array("Video.recom = '1'")));
        }

        return false;
    }

    /**
     */
    public function search()
    {
        $searchq = Sanitize::paranoid($this->params['url']['searchq']);

        /*
        http://book.cakephp.org/de/view/153/Data-Sanitization
        * 4.2.1 paranoid
        * 4.2.2 html
        * 4.2.3 escape
        * 4.2.4 clean
        */

        if ($searchq !== null) {
            $criteria = " `Video`.`songtitle` LIKE '%" . $searchq . "%' OR `Video`.`bandname` LIKE '%" . $searchq . "%'  OR `Video`.`tags` LIKE '%" . $searchq . "%'  ";
        } else {
            $criteria = " `Video`.`descr` LIKE '%nuit%'";
        }

        $paging['sortBy']    = "date";
        $paging['direction'] = 'DESC';

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid  = $this->Video->findAll($criteria, "", $order, $limit, $page);
        $arTmpCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name ASC');

        //print "<pre>"; print_r($arTmpCatSubCats); print "</pre>";

        if (isset($arTmpVid)) {
            //$this->set(compact('comments','currentDateTime'));
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = 'Search';
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
        //	print_r($this->Session ->read("User"));
    }

    /**
     * @param int $limit
     *
     * @return false
     */
    public function getRandomVideoTags($limit = 10)
    {
        if (!is_numeric($limit)) {
            $this->redirect('/');
        }
        if (isset($this->params['requested']) && $this->params['requested']) {
            //return 	$this->Company->find('all', array('order' => 'Company.id DESC', 'limit' => $limit));
            return $this->Video->query("SELECT tags FROM `videos` WHERE tags != '' GROUP BY tags LIMIT " . $limit . " ");
        }

        return false;
    }

    /**
     *
     * @return mixed
     */
    public function statistics()
    {

        /* get categories
        ------------------------------------------------*/
        $sSQL  = "select category_id, count(*)as total from videos WHERE category_id!=0 GROUP BY category_id ORDER BY total DESC";
        $arTmp = $this->Video->query($sSQL);

        foreach ($arTmp as $sTmp) {
            //$dataByCategory[$this->requestAction('videos/getcategorynamebyid/'.$sTmp["videos"]["category_id"])."-".$sTmp[0]["total"]] = $sTmp[0]["total"];
            if ($sTmp[0]["total"] > 10) {
                $dataByCategory[$this->requestAction('videos/getcategorynamebyid/' . $sTmp["videos"]["category_id"])] = $sTmp[0]["total"];
            }
        }
        //print "<pre>"; print_r($dataByCategory); print "</pre>"; die();
        $this->set("dataByCategory", $dataByCategory);
        return $dataByCategory;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getcategorynamebyid($id)
    {
        $sSQL  = "select * from categories WHERE id= " . $id;
        $arTmp = $this->Category->query($sSQL);

        return $arTmp[0]['categories']['name'];
    }

    /**
     * @return false
     */
    public function menuCategories()
    {
        //if(!is_numeric($limit)) $this->redirect('/');
        if (isset($this->params['requested']) && $this->params['requested']) {
            //return 	$this->Company->find('all', array('order' => 'Company.id DESC', 'limit' => $limit));
            return $this->Category->query("SELECT * FROM categories ORDER BY categories.name ASC");
        }

        return false;
    }

    /**
     * @param $searchq
     *
     * @return false
     */
    public function getRelatedVideos($searchq)
    {
        //echo $searchq = Sanitize::paranoid($this->params['url']['searchq']);
        //echo $searchq = $this->params['url']['searchq'];

        $arKeys = explode(" ", $searchq);
        if (count($arKeys) > 0) {
            foreach ($arKeys as $keyQ) {
                if ($keyQ[0] !== "") {
                    $sSQLB .= " AND bandname LIKE '%" . $keyQ[0] . "%' ";
                    $sSQLB .= " OR tags LIKE '%" . $keyQ[0] . "%' ";
                }
                if ($keyQ[1] !== "") {
                    $sSQLB .= " OR songtitle LIKE '%" . $keyQ[1] . "%' ";
                }
            }
            /*
                foreach($arKeys as $keyQ){
                    $sSQLT.= " OR songtitle LIKE '%".$keyQ."%' ";
                }
            */
        }

        //if(!is_numeric($limit)) $this->redirect('/');
        if (isset($this->params['requested']) && $this->params['requested']) {
            //return 	$this->Company->find('all', array('order' => 'Company.id DESC', 'limit' => $limit));
            return $this->Video->query("SELECT * FROM `videos` WHERE bandname !='' " . $sSQLB . "  OR songtitle LIKE '%remix%' ORDER BY RAND() LIMIT 8 ");
        }

        return false;
    }

    /**
     * @param null $id
     *
     * @return false
     */
    public function removedbyyoutube($id = null)
    {
        if (isset($this->params['requested']) && $this->params['requested']) {
            return $this->Video->query("UPDATE `videos` SET hasimg = 0,removed = 1 WHERE id='" . $id . "' ");
        }

        return false;
    }

    /**
     * @param int $limit
     *
     * @return false
     */
    public function list_last_topvideos($limit = 9)
    {
        if (!is_numeric($limit)) {
            $this->redirect('/');
        }
        if (isset($this->params['requested']) && $this->params['requested']) {
            return $this->Video->find('all', array('order' => 'Video.views DESC', 'limit' => $limit, 'conditions' => array("Video.removed = '0'")));
        }

        return false;
    }

    /*----------------------------------------------------------
    / Copy Video Image To Local - not in use - this func grab the image from YouTube and save it localy
    / @author: Maran Emil
    ----------------------------------------------------------*/

    /*
    function copyImageToLocal(){

        $this->layout = "ajax";

        $imgpath	= urldecode($this->params['url']['imgpath']);
        $pic_width	= $this->params['url']['pic_width'];
        $pic_height	= $this->params['url']['pic_height'];
        $idpic		= $this->params['url']['idpic'];

        $destPathFd	= $this->params['url']['folder']."/";
        if(!$destPathFd) $destPathFd = date("Ymd")."/";

        $destPath	= APP.'/webroot/imgvd/'.$destPathFd;

        if(!is_dir($destPath))
        {
            mkdir($destPath,0777);
        }

        $vidFileT	= APP.'webroot/imgvd/'.$destPathFd.'pic_'.$idpic.".jpg";
        if(file_exists($vidFileT) && filesize($vidFileT) < 500)
        {
            unlink($vidFileT);
        }

        // if image not found on server
        if(!file_exists($vidFileT)){

                $sImgData = file_get_contents($imgpath);

                //if(!$sImgData) $sImgData = file_get_contents("http://i4.ytimg.com/vi/Kd0ISei5qhk/default.jpg");

                if(!$sImgData)
                {
                    $sImgData = file_get_contents(APP.'webroot/imgvd/default.jpg');
                }

                $sFile	= $vidFileT;
                $fp		= fopen($sFile,"w");
                fwrite($fp,$sImgData);
        }

    }
    */

    /*----------------------------------------------------------
    / Generate Thumb Video Image To Local - not in use
    / @author: Maran Emil
    ----------------------------------------------------------*/

    /*
    function genThumb($idpic,$biggestSide,$ceva,$destPathFd){

        $destPath = APP.'/webroot/imgvd/'.$destPathFd;

        if(!is_dir($destPath)){
                mkdir($destPath,0777);
        }

        //Your Image
        $imgSrc =APP.'/webroot/imgvd/'.$destPathFd.'pic_'.$idpic.".jpg";

        //getting the image dimensions
        list($width, $height) = getimagesize($imgSrc);

        //create image from the jpeg
        $myImage = imagecreatefromjpeg($imgSrc);

        if($width > $height){
            $biggestSide = $width; //find biggest length
        }
        else{
            $biggestSide = $height;
        }

        //The crop size will be half that of the largest side
        //$cropPercent = .7; // This will zoom in to 50% zoom (crop)
        $cropPercent = min($biggestSide/$width, $biggestSide/$height);

        $cropWidth   = $biggestSide*$cropPercent;
        $cropHeight  = $biggestSide*$cropPercent;

        //getting the top left coordinate
        $cropWidthx = ($width-$cropWidth)/2;
        $cropHeighty = ($height-$cropHeight)/2;

        $thumbSizex = 120; // will create a 250 x 250 thumb
        $thumbSizey = 90; // will create a 250 x 250 thumb
        $thumb = imagecreatetruecolor($thumbSizex, $thumbSizey);
        $source = imagecreatefromjpeg($imgSrc);
        //imagefilter($image, IMG_FILTER_CONTRAST, 255);

        // Make it smaller
        imagecopyresampled($thumb, $myImage, 0, 0, 0, 0, $width, $height, $width, $height);
        //imagecopyresampled($thumb, $myImage, -35, 0, $cropWidthx, $cropHeighty, $thumbSizex, $thumbSizey, $cropWidth, $cropHeight);
        //imagecopyresized($this->thumb, $this->myImage, 0, 0,$this->x, $this->y, $thumbSize, $thumbSize, $this->cropWidth, $this->cropHeight);

        if(!imagejpeg($thumb,  APP.'/webroot/imgvd/'.$destPathFd.'pic_thumb'.$idpic.".jpg",100))
        {
            echo "error";
        }

    }
    */

} // end class


