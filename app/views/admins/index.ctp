<?php
// Creo il navigatore.
$html->addCrumb(__("Home", TRUE), "/");
?>

<?php foreach ($arTmpVid as $sVid): ?>
	<?php echo $this->renderElement("video", $sVid); ?>
<?php endforeach; ?>
<?php //print_r($arTmpUsr)?>
<? echo $this->renderElement('pagination', $paging);?> 
