<form method="post" action="<?php echo $html->url('/users/forgotpassword') ?>">
    <div class="register_group">Email:</div>
    <div class="register_input">
	   <?php echo $form->text('User/username', array('size' => '15', 'class' => 'input_medium')); ?><br/>
    </div>
    <div class="register_group"></div>
    <div class="register_input">
	   <?php echo $form->submit('Continua'); ?>
    </div>
</form>
<BR>
