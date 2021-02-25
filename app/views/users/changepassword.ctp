<form method="post" action="<?php echo $html->url('/users/changepassword') ?>">
    <!-- old password -->
    <div class="register_group">Parola veche:</div>
    <div class="register_input">
	   <?php echo $form->text('User/oldpassword', array('size' => '15', 'class' => 'input_medium')); ?><br/>
    </div>
    <!-- old password -->
    <!-- new password 1 -->
    <div class="register_group">Parola noua:</div>
    <div class="register_input">
	   <?php echo $form->text('User/newpassword1', array('size' => '15', 'class' => 'input_medium')); ?><br/>
    </div>
    <!-- new password 1 -->
    <!-- new password 2 -->
    <div class="register_group">Re-introdu Parola noua:</div>
    <div class="register_input">
	   <?php echo $form->text('User/newpassword2', array('size' => '15', 'class' => 'input_medium')); ?><br/>
    </div>
    <!-- new password 2 -->
    <div class="register_group"></div>
    <div class="register_input">
	   <?php echo $form->submit('Schimba Parola'); ?>
    </div>
</form>