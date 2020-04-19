<?php
// Creo il navigatore.
$html->addCrumb(__("Home", TRUE), "/");
?>
<?php echo $this->renderElement("video_ajax"); ?>
<?php
if ($arTmpVid) {
    foreach ($arTmpVid as $sVid):
        echo $this->renderElement("video", $sVid);
    endforeach;
    echo $this->renderElement('pagination', $paging);
} else {
    echo "No videos found...";
}
?>