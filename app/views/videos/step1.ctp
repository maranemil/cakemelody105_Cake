<?php //echo $this->renderElement('post',$post); ?>
<?php //print_r($arTmpCat); ?>

<?php
foreach ($arTmpCat as $itemSel) {
    $Categories .= "
		<option value='" . $itemSel["categories"]["id"] . "'> " . $itemSel["categories"]["name"] . "</option >
		";
}
?>
<div class="registerBox">
    <form method="post" action="<?php echo $html->url('/videos/step2/'); ?>" enctype="multipart/form-data">
        <div class="register_group"><b>Select Video Category </b></div>
        <div class="register_input">
            <select name="data[Video][category_id]" id="Article/category_id">
                <?= $Categories ?>
            </select>
        </div>
        <div class="register_group"><b>Band Name:</b> ( Example: Kylie Minogue )</div>
        <div class="register_input">
            <?php echo $form->text('Video/bandname', array('size' => '20', 'value' => $post[0]['Video']['bandname'])); ?>
        </div>
        <div class="register_group"><b>Song Title</b> ( Example: Wouldn't Change a Thing )</div>
        <div class="register_input">
            <?php echo $form->text('Video/songtitle', array('size' => '20', 'value' => $post[0]['Video']['songtitle'])); ?>
        </div>
        <div class="register_group"><b>Tags:</b> (optional)</div>
        <div class="register_input">
            <?php echo $form->text('Video/tags', array('size' => '20', 'value' => $post[0]['Video']['tags'])); ?>
        </div>
        <br/>
        <div class="switchstep">
            <b>Please enter Your youtube code or youtube URL Here.</b> <br/>
            <b>Example1 :</b> http://www.youtube.com/watch?v=0bREy8pQX3U <br/>
            <b>Example2 :</b> 0bREy8pQX3U <br/>
        </div>
        <br/>
        <div class="register_group"><b>Youtube URL or CODE</b>:</div>
        <div class="register_input">
            <?php echo $form->text('Video/tubecode', array('size' => '20', 'value' => $post[0]['Video']['tubecode'])); ?>
        </div>
        <BR><BR>
        <?php echo $form->submit('Add video'); ?>
    </form>
</div>

