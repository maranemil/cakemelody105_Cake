<?php
// Creo il navigatore.
//$html->addCrumb(__("Home", TRUE), "/");
?>
<?php echo $this->renderElement("video_ajax"); ?>
<?php foreach ($arTmpVid as $sVid): ?>
   <?php echo $this->renderElement("video", $sVid); ?>
<?php endforeach; ?>
<?php //print_r($arTmpUsr)?>
<?php echo $this->renderElement('pagination', $paging); ?>
