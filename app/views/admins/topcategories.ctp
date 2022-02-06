<?php /** @noinspection PhpUndefinedVariableInspection */
// Creo il navigatore.
$html->addCrumb(__("Home", true), "/");
?>
<?php foreach ($arTmpVid as $sVid): ?>
    <?php echo $this->renderElement("video", $sVid); ?>
<?php endforeach; ?>
<?php //print_r($arTmpUsr)?>
<?php echo $this->renderElement('pagination', $paging); ?>
