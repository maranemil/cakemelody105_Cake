<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection CallableParameterUseCaseInTypeContextInspection */
/** @noinspection SqlNoDataSourceInspection */
/** @noinspection SqlDialectInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUnused */
/** @noinspection PhpDynamicAsStaticMethodCallInspection */
/** @noinspection SuspiciousAssignmentsInspection */
/**
 * Controller Videos
 * @author         Maran Emil | Maran Project | maran_emil@yahoo.com
 * @copyright      Copyright 2009, Maran Project.
 * @link           http://maran-emil.de
 * @version        1.0
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 */

App::import('Sanitize');

/**
 * Class AdminsController
 */
class AdminsController extends AppController
{

    public $name       = "Admins";
    public $uses       = array('Video', 'User', 'Category');
    public $helpers    = array('Html', 'Javascript', 'Session', 'Head', 'Javascript', 'Ajax', 'Form', 'Pagination');
    public $components = array('Pagination', 'Upload');

    /**
     * Index videos
     */
    public function index()
    {
        // print_r($this->Session ->read("User"));
        // $this->layout = "ajax";
        // $this->checkIfLoggedasAdmin();
        $this->redirect("/admins/listvideos");
    }

    /**
     * @param null $id
     */
    public function deletevideo($id = null)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->del($id);
    }

    /**
     * @param null $id
     */
    public function addrecomendedvideo($id = null)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->query('UPDATE videos SET recom=1 WHERE id = ' . $id . '  ');
    }

    /**
     * @param null $id
     */
    public function remrecomendedvideo($id = null)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->query('UPDATE videos SET recom=0 WHERE id = ' . $id . '  ');
    }

    /**
     * @param null $id
     */
    public function removedbyyoutube($id = null)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->query("UPDATE `videos` SET hasimg = 0,removed = 1 WHERE id='" . $id . "' ");
    }

    /**
     * @param null $id
     */
    public function resetbyyoutube($id = null)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->query("UPDATE `videos` SET hasimg = 1,removed = 0 WHERE id='" . $id . "' ");
    }

    /**
     *
     */
    public function listvideos()
    {
        // $this->checkIfLoggedasAdmin();
        $criteria = null;
        // $criteria = " `Video`.`removed` = 0 ";
        $paging['sortBy']    = "id";
        $paging['direction'] = 'DESC';
        $paging['show']      = '30';

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid  = $this->Video->findAll($criteria, "", $order, $limit, $page);
        $arTmpCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name');

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = 'Cakemelody - Videos';
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
        // print_r($this->Session ->read("User"));
    }

    /**
     *
     */
    public function listvideosremoved()
    {
        $this->checkIfLoggedasAdmin();
        $criteria = " `Video`.`removed` = 1 ";

        $paging['sortBy']    = "id";
        $paging['direction'] = 'DESC';
        $paging['show']      = '30';

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid  = $this->Video->findAll($criteria, "", $order, $limit, $page);
        $arTmpCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name');

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = 'Cakemelody - Videos';
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);
        // print_r($this->Session ->read("User"));
    }

    public function listcategories()
    {
    }

    /**
     * @param int $id
     *
     * @return mixed|string
     */
    public function showcategory($id)
    {
        $this->checkIfLoggedasAdmin();
        if (!is_numeric($id)) {
            $this->redirect('/');
        }
        $currCat = "Default";

        $arTmpCatSubCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name');

        foreach ($arTmpCatSubCats as $sCat) {
            if ($sCat['categories']["id"] === $id) {
                $currCat = $sCat['categories']["name"];
                break;
            }
        }
        return $currCat;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getcategorynamebyid($id)
    {
        $this->checkIfLoggedasAdmin();
        $sSQL  = "select * from categories WHERE id= " . $id;
        $arTmp = $this->Category->query($sSQL);
        return $arTmp[0]['categories']['name'];
    }

    /**
     * @param int|null $id
     */
    public function view($id = null)
    {
        $this->checkIfLoggedasAdmin();
        $this->Video->id = $id;
    }

    /**
     */
    public function search()
    {
        $searchq = Sanitize::paranoid($this->params['url']['searchq']);
        $this->checkIfLoggedasAdmin();

        /*
        http://book.cakephp.org/de/view/153/Data-Sanitization
        * 4.2.1 paranoid
        * 4.2.2 html
        * 4.2.3 escape
        * 4.2.4 clean
        */
        $criteria = " `Video`.`descr` LIKE '%nuit%'";
        if ($searchq !== null) {
            $criteria = " `Video`.`songtitle` LIKE '%" . $searchq . "%' OR `Video`.`bandname` LIKE '%" . $searchq . "%'  OR `Video`.`tags` LIKE '%" . $searchq . "%'  ";
        }

        $paging['sortBy']    = "date";
        $paging['direction'] = 'DESC';

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid  = $this->Video->findAll($criteria, "", $order, $limit, $page);
        $arTmpCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name');

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
        //print_r($this->Session ->read("User"));

    }

    /*
    function optimizepics($idArea){

        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $criteria = null;

        if($idArea==1){ // pics
            $arTmpPics = $this->Picture->findAll($criteria,"", $order, $limit, $page);
        }
        else if($idArea==3){ // videos
            $arTmpPics = $this->Picture->findAll($criteria,"", $order, $limit, $page);
        }
        else if($idArea==2){ // videos
            $arTmpVid = $this->Video->findAll($criteria,"", $order, $limit, $page);
        }

        if($idArea==1){ // pics

            //print_r($arTmpPics);
            $cnt = 0;
            foreach($arTmpPics as $disPic){
                $cnt++;

                $disPicPath = str_replace("-","",$disPic["Picture"]["date"]);
                // APP.'webroot/imgbk/pic_'.$idpic.".jpg";
                $disPicPathBase = APP.'webroot/imgbkthumb/'.$disPicPath.'/';
                $disPicPathBasePic =  APP.'webroot/imgbkthumb/'.$disPicPath.'/pic_thumb'.$disPic["Picture"]["id"].".jpg";
                $disPicPathBasePicOld =  APP.'webroot/imgbkthumb/pic_thumb'.$disPic["Picture"]["id"].".jpg";

                if(!is_dir($disPicPathBase)){
                    mkdir($disPicPathBase,0777);
                }

                if(file_exists($disPicPathBasePicOld)){
                    copy($disPicPathBasePicOld,$disPicPathBasePic);
                    unlink($disPicPathBasePicOld);
                }

                //echo $disPic["Picture"]["id"].".jpg <br />";
                //if($cnt==120) die();
            }
        }

        if($idArea==3){ // pics alternative

            //print_r($arTmpPics);
            $cnt = 0;
            foreach($arTmpPics as $disPic){
                $cnt++;

                $disPicPath = str_replace("-","",$disPic["Picture"]["date"]);
                // APP.'webroot/imgbk/pic_'.$idpic.".jpg";
                $disPicPathBase = APP.'webroot/imgbk/'.$disPicPath.'/';
                $disPicPathBasePic =  APP.'webroot/imgbk/'.$disPicPath.'/pic_'.$disPic["Picture"]["id"].".jpg";
                $disPicPathBasePicOld =  APP.'webroot/imgbk/pic_'.$disPic["Picture"]["id"].".jpg";

                if(!is_dir($disPicPathBase)){
                    mkdir($disPicPathBase,0777);
                }

                if(file_exists($disPicPathBasePicOld)){
                    copy($disPicPathBasePicOld,$disPicPathBasePic);
                    unlink($disPicPathBasePicOld);
                }
                //echo $disPic["Picture"]["id"].".jpg <br />";
                //if($cnt==120) die();
            }

            //print_r($arTmpVid[0]); die();
        }


        if($idArea==2){ // videos

            $cnt = 0;
            foreach($arTmpVid as $disPic){
                $cnt++;

                $disPicPath = str_replace("-","",$disPic["Video"]["date"]);
                // APP.'webroot/imgbk/pic_'.$idpic.".jpg";

                //$disPicPathBase = APP.'webroot/imgvd/'.$disPicPath.'/';
                //$disPicPathBasePic =  APP.'webroot/imgvd/'.$disPicPath.'/pic_'.$disPic["Video"]["id"].".jpg";
                //$disPicPathBasePicOld =  APP.'webroot/imgvd/pic_'.$disPic["Video"]["id"].".jpg";

                $disPicPathBase = APP.'webroot/imgvd/'.$disPicPath.'/';
                $disPicPathBasePic =  APP.'webroot/imgvd/'.$disPicPath.'/pic_thumb'.$disPic["Video"]["id"].".jpg";
                $disPicPathBasePicOld =  APP.'webroot/imgvd/pic_thumb'.$disPic["Video"]["id"].".jpg";

                if(!is_dir($disPicPathBase)){
                    mkdir($disPicPathBase,0777);
                }

                if(file_exists($disPicPathBasePicOld)){
                    copy($disPicPathBasePicOld,$disPicPathBasePic);
                    unlink($disPicPathBasePicOld);
                }
                //die();
                //echo $disPic["Picture"]["id"].".jpg <br />";
                //if($cnt==120) die();
            }
        }
    }
    */

}