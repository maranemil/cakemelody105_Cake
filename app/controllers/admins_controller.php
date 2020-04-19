<?php
/**
 * Controller Videos
 *
 * @author        Maran Emil | Maran Project | maran_emil@yahoo.com
 * @copyright    Copyright 2009, Maran Project.
 * @link        http://maran-emil.de
 * @version        1.0
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 */

App::import('Sanitize');

class AdminsController extends AppController
{

    var $name = "Admins";
    var $uses = array('Video', 'User', 'Category');
    var $helpers = array('Html', 'Javascript', 'Session', 'Head', 'Javascript', 'Ajax', 'Form', 'Pagination');
    var $components = array('Pagination', 'Upload');

    /*----------------------------------------------------------
    / Index videos
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function index()
    {
        //	print_r($this->Session ->read("User"));
        //$this->layout = "ajax";
        //$this->checkIfLoggedasAdmin();
        $this->redirect("/admins/listvideos");
    }

    /*----------------------------------------------------------
    / delete video
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function deletevideo($id = NULL)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->del($id);
    }

    /*----------------------------------------------------------
    / add recomended video
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function addrecomendedvideo($id = NULL)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->query('UPDATE videos SET recom=1 WHERE id = ' . $id . '  ');
    }

    /*----------------------------------------------------------
    / remmove recomended video
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function remrecomendedvideo($id = NULL)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->query('UPDATE videos SET recom=0 WHERE id = ' . $id . '  ');
    }

    /*----------------------------------------------------------
    / set as removed video from youtube
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function removedbyyoutube($id = NULL)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->query("UPDATE `videos` SET hasimg = 0,removed = 1 WHERE id='" . $id . "' ");
    }


    /*----------------------------------------------------------
    / reset by removed youtube video
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function resetbyyoutube($id = NULL)
    {
        $this->layout = "ajax";
        $this->checkIfLoggedasAdmin();
        $this->Video->query("UPDATE `videos` SET hasimg = 1,removed = 0 WHERE id='" . $id . "' ");
    }


    /*----------------------------------------------------------
    / list videos
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function listvideos()
    {
        //$this->checkIfLoggedasAdmin();
        $criteria = null;
        //$criteria = " `Video`.`removed` = 0 ";
        $paging['sortBy'] = "id";
        $paging['direction'] = 'DESC';
        $paging['show'] = '30';

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid = $this->Video->findAll($criteria, "", $order, $limit, $page);
        $arTmpCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name ASC');

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = 'Cakemelody - Videos';
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);

        //	print_r($this->Session ->read("User"));
    }


    /*----------------------------------------------------------
    / list videos removed
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function listvideosremoved()
    {
        $this->checkIfLoggedasAdmin();
        $criteria = null;
        $criteria = " `Video`.`removed` = 1 ";

        $paging['sortBy'] = "id";
        $paging['direction'] = 'DESC';
        $paging['show'] = '30';

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid = $this->Video->findAll($criteria, "", $order, $limit, $page);
        $arTmpCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name ASC');

        if ($arTmpVid) {
            $this->set("arTmpVid", $arTmpVid);
            $this->set("arTmpCats", $arTmpCats);
            $this->pageTitle = 'Cakemelody - Videos';
        } else {
            $this->redirect('/');
        }

        $arTmpUsr = $this->Session->read("User");
        $this->set("arTmpUsr", $arTmpUsr);

        //	print_r($this->Session ->read("User"));
    }


    /*----------------------------------------------------------
    / list categories
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function listcategories()
    {


    }

    /*----------------------------------------------------------
    / show category
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function showcategory($id)
    {
        $this->checkIfLoggedasAdmin();
        if (!is_numeric($id)) $this->redirect('/');
        $currCat = "Default";

        $arTmpCatSubCats = $this->Category->query('SELECT * FROM categories ORDER BY categories.name ASC');

        foreach ($arTmpCatSubCats as $sCat) {
            if ($sCat['categories']["id"] == $id) {
                $currCat = $sCat['categories']["name"];
            }
        }
        return $currCat;
    }

    /*----------------------------------------------------------
    / get category name by id
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function getcategorynamebyid($id)
    {
        $this->checkIfLoggedasAdmin();
        $sSQL = "select * from categories WHERE id= " . $id . "";
        $arTmp = $this->Category->query($sSQL);
        return $arTmp[0]['categories']['name'];


    }

    /*----------------------------------------------------------
    / view video
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function view($id = NULL)
    {
        $this->checkIfLoggedasAdmin();
        $this->Video->id = $id;
    }


    /*----------------------------------------------------------
    / search
    / @author: Maran Emil
    ----------------------------------------------------------*/

    function search($searchq = NULL)
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

        if ($searchq != NULL) {
            $criteria = " `Video`.`songtitle` LIKE '%" . $searchq . "%' OR `Video`.`bandname` LIKE '%" . $searchq . "%'  OR `Video`.`tags` LIKE '%" . $searchq . "%'  ";
        } else {
            $criteria = " `Video`.`descr` LIKE '%nuit%'";
        }

        $paging['sortBy'] = "date";
        $paging['direction'] = 'DESC';

        list($order, $limit, $page) = $this->Pagination->init($criteria, $paging);
        $arTmpVid = $this->Video->findAll($criteria, "", $order, $limit, $page);
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

        //print_r($this->Session ->read("User"));


    }


    /*----------------------------------------------------------
    / optimizepics - not in use
    / @author: Maran Emil
    ----------------------------------------------------------*/

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

?>
