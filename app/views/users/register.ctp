<h3>Registrierung</h3>
<form method="post" action="<?php echo $html->url('/users/register'); ?>">

<div class="register_group">
Vorname:<br />
<?php echo $form->text('User/name', array('size' => '20')); ?>
</div>
<div class="register_group">
Nachname:<br />
<?php echo $form->text('User/pastname', array('size' => '20')); ?>
</div>
<div class="register_group">
Passwort: <span class="red">*</span><br />
<?php echo $form->password('User/password', array('size' => '15')); ?>
<?php echo $form->error('User/password', 'Passwort muss angegeben werden'); ?>
</div>
<div class="register_group">
Passwort verify: <span class="red">*</span><br />
<?php echo $form->password('User/password_confirm', array('size' => '15')); ?><br />
<span class="error-message"><?php if($error == "true") { echo "Passwort stimmt nicht &uuml;berein"; } else {} ?></span>
</div>
<div class="register_group">
E-Mail Adresse: <span class="red">*</span><br />
<?php echo $form->text('User/e-mail', array('size' => '50')); ?>
<?php echo $form->error('User/e-mail', 'E-Mail Adresse muss angegeben werden'); ?>
</div>
<div class="register_group">Email Adresse verify: <span class="red">*</span><br />
<?php echo $form->text('User/username', array('size' => '15', 'class' => 'input_big')); ?>
<?php echo $form->error('User/username', 'Email muss angegeben werden'); ?>
</div>
<?php echo $form->submit('Registrieren'); ?>
</form>
<div class="add_footer">Mit <span class="red">*</span> gekennzeichnete Felder sind unbedingt auszuf&uuml;llen</div>