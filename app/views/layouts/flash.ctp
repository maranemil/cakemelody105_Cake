<?php
/* SVN FILE: $Id: flash.ctp 6311 2008-01-02 06:33:52Z phpnut $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.templates.layouts
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 6311 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2008-01-01 22:33:52 -0800 (Tue, 01 Jan 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $page_title; ?></title>
<?php echo $html->charset(); ?>

		<?php echo $html->meta('icon'); ?>
		<?php echo $html->css('default'); ?>
		<?php echo $html->css('jquery-impromptu.3.1'); ?>	
		<?php echo $html->css('tipTip'); ?>
	
		<?php  echo $javascript->link('jquery'); ?>
		<?php  echo $javascript->link('customweb'); ?>
		<!-- jquery.tipTip -->
		<?php  echo $javascript->link('jquery.tipTip.minified'); ?>
		<!-- jquery-impromptu -->
		<?php  echo $javascript->link('jquery-impromptu.3.1'); ?>

		<?php //echo $head->registered() ?>
	
		<?php if ($session->check('Message.flash')): ?>
			<?php echo $javascript->link('flash_message'); ?>
			<?php echo $html->css('flash_message'); ?>
		<?php endif; ?>




<?php if (Configure::read() == 0) { ?>
	<meta http-equiv="Refresh" content="<?php echo $pause; ?>;url=<?php echo $url; ?>"/>
<?php } ?>
<style>
	body { /*margin: 15% 30% 20% 30%;*/; background: #444;}
	P { text-align:center; font: bold 14px Calibri,sans-serif }
	A { color:#333; text-decoration:none; font: bold 14px Calibri,sans-serif}
	A:HOVER { text-decoration: underline; color:#44E }

	#wrapper{ margin: 10% 20%; width: 500px; border: 1px solid red; height: 200px}
	

</style>
</head>

<body>

	<p style="">
		<a href="<?php echo $url; ?>">
			<script> $.prompt('<?php echo $message; ?>'); </script>
		</a>
	</p>
	
</body>
</html>