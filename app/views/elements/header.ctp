<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
   <?php echo $html->charset(); ?>
    <title>
	   <?php
	   $title_for_layout = $Video[0]['videos']['bandname'] . " " . $Video[0]['videos']['songtitle'];
	   if (!$title_for_layout) {
		  $title_for_layout = " Keep your favourite links in your list ";
	   }
	   $title_for_layout .= " Eopp cakeMelody Social Bookmark 2.0 Cakephp ";
	   echo $title_for_layout;
	   ?>
    </title>
    <!--
        This website is powered by CAKEPHP -
        CakePHP : the rapid development php framework
        CakePHP enables PHP users at all levels to rapidly develop robust web applications.

        CAKEPHP is a free open source Framework licensed under GNU/GPL.
        Information and contribution at http://cakephp.org/
    -->
    <meta name="keywords"
          content="Social Portal, Youtube Songs, Favourites, Progressive House, Trance, Best Rock Songs, Blues, Dance, Hip-Hop, Latin, Pop, R'n'B, Rock & Alternative, World & Reggae, Music Youtube "/>
    <meta name="description" content="Eopp cakeMelody- Social Portal - Keep your favourite songs in your list"/>
   <?php echo $html->meta('icon'); ?>
   <?php echo $html->css('default'); ?>
   <?php echo $html->css('jquery-impromptu.3.1'); ?>
   <?php echo $html->css('tipTip'); ?>
   <?php echo $javascript->link('jquery-1.4.2.min.js'); ?>
   <?php echo $javascript->link('jquery_ui.js'); ?>
   <?php //echo $javascript->link('boxgrid.js'); ?>
   <?php echo $javascript->link('jquery.tipTip.minified'); ?> <!-- jquery.tipTip -->
   <?php echo $javascript->link('jquery-impromptu.3.1'); ?> <!-- jquery-impromptu -->
    <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script> -->
   <?php // echo $javascript->link('customweb'); ?>
   <?php echo $head->registered() ?>
   <?php if ($session->check('Message.flash')): ?>
	  <?php echo $javascript->link('flash_message'); ?>
	  <?php // echo $html->css('flash_message'); ?>
   <?php endif; ?>
   <?php if (isset($scripts_for_layout)) {
	  echo $scripts_for_layout;
   } ?>
   <?
   if (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.')) {
	  echo '<meta http-equiv="X-UA-Compatible" content="IE=7" />';
   }
   $session->activate('/');
   ?>
    <script type="text/javascript">
		// tiptip tooltip jquery
		$(function () {
			//$(".tipnfo").tipTip({ maxWidth: "200px", edgeOffset: 10}); //
		});
    </script>
   <?php echo $javascript->link('okzoom'); ?>
    <script type="text/javascript">
		$(function () {
			$('.okzoomlnk').okzoom({
				scaleWidth: 700,
				backgroundRepeat: "repeat",
				width: 250,
				height: 250
			});
		});
    </script>
</head>
<body>
<?php
//phpinfo(); //PHP Version 5.3.5
/*print "<pre>";
print_r($session);
print_r($_POST);
print "</pre>";*/
?>
<?php
//	require($_SERVER["DOCUMENT_ROOT"]."/x_Stat/ajax_include.php");
?>
<!-- Navigatore -->
<div id="navxbar">
    <div id="navbar">
        <div style="float: left; margin: 8px 20px 5px 0">
		   <?php
		   if ($session->read("User.username")) {
			  ?>
               Logged as: &nbsp; <A
                       HREF="<?= $this->webroot ?>users/view/<?= $session->read("User.id") ?>"><?= $session->read("User.name") ?> <?= $session->read("User.pastname") ?></A> |
               <A HREF="<?= $this->webroot ?>users/logout">Logout</A>
		   <?php }
		   else {
			  ?>
               <form method="post" action="<?= $this->webroot ?>users/login/">
                   <!-- <?php echo $html->url('/users/login/') ?> -->
                   <TABLE>
                       <TR>
                           <TD>Login:
                               &nbsp; <?php echo $form->text('User/username', array('size' => '15', 'class' => 'input_medium', 'value' => 'email@', 'onfocus' => 'this.value=""')); ?> </TD>
                           <TD><?php echo $form->password('User/password', array('size' => '15', 'class' => 'input_medium', 'value' => 'password', 'onfocus' => 'this.value=""')); ?> </TD>
                           <TD><?php echo $form->submit('Login'); ?></TD>
                           <!-- <TD><A HREF="<?= $this->webroot ?>users/register">Register</TD> -->
                       </TR>
                   </TABLE>
               </form>
		   <? } ?>
        </div>
        <!-- Search Box -->
        <div id="searchform" style="float: right; margin: 10px 20px 5px 0">
		   <?php if ($this->params['controller'] == "admins") { ?>
			  <?php echo $form->create('Search', array('id' => "searchform", 'type' => "get", 'url' => "/admins/search")); ?>
			  <?
		   }
		   else {
			  ?>
			  <?php echo $form->create('Search', array('id' => "searchform", 'type' => "get", 'url' => "/videos/search")); ?>
		   <?php } ?>
            <label for="searchq">
                <input type="text" name="searchq" id="searchq" size="30" value="<?php __("Search"); ?>"
                       onfocus='this.value=""'/>
            </label>
            </form>
        </div>
        <!-- / Search Box -->
    </div>
</div>
<!-- / Navigatore -->
<div id="breadcrumb" style="">
    <!-- You are here: &nbsp; --><!--  http:// --><?php //$_SERVER['HTTP_HOST']?><?php //$_SERVER['REQUEST_URI']?>
    <div id="menutop" style="float: left">
        <a href="<?= $this->webroot ?>">Home</a> |
        <a href="<?= $this->webroot ?>videos/topvideos/">Top Videos</a> |
        <a href="<?= $this->webroot ?>videos/newvideos/">Fresh Videos</a> |
        <a href="<?= $this->webroot ?>infos/contactus/">Impressum</a> |
	   <?php if (!$session->read("User.username")) { ?>
           <a href="<?= $this->webroot ?>users/register/">Register</a> |
           <a href="<?= $this->webroot ?>users/login/">Login </a> |
	   <?php } ?>
        <a href="<?= $this->webroot ?>infos/whatisthis/">What is this?</a> |
        <a href="<?= $this->webroot ?>infos/cakephpapps/">Cakephp Resources</a> |
    </div>
    <div id="menutoplogo" style="float: right"></div>
    <div style="clear: both"></div>
</div>
<div id="middlepage" style="">
    <!-- Contenitore -->
    <div id="content">
        <!-- Contenitore Sinistro -->
        <div id="contentleft">
		   <?php if ($session->check('Message.flash')): ?>
		   <?php $session->flash(); ?>
<?php endif; ?>