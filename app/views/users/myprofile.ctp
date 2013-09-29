<?php
// Creo il navigatore.
$html->addCrumb(__("Home", TRUE), "/");
?>

<h1>Profil von <?php echo $user['User']['name']." ".$user['User']['pastname']; ?></h1>

<form method="post" enctype="multipart/form-data" action="<?php echo $html->url('/users/savemyprofile/'.$user['User']['id']); ?>">

<div class="register_group">
Vorname:<br />
<?php echo $form->text('User/name', array('size' => '20','value'=>$user['User']['name'])); ?>
</div>

<div class="register_group">
Nachname:<br />
<?php echo $form->text('User/pastname', array('size' => '20','value'=>$user['User']['pastname'])); ?>
</div>

<div class="register_group">
Nickname:<br />
<?php echo $form->text('User/nickname', array('size' => '20','value'=>$user['User']['nickname'])); ?>
</div>

<div class="register_group">
E-Mail Adresse: <span class="red">*</span><br />
<?php echo $form->text('User/e-mail', array('size' => '20','value'=>$user['User']['e-mail'],'readonly' => 'readonly')); ?>
<?php echo $form->error('User/e-mail', 'E-Mail Adresse muss angegeben werden'); ?>
</div>
<div class="register_group">Interests: <span class="red">*</span><br />
<?php echo $form->text('User/interests', array('style' => 'width:200px; height: 150px','type'=>'textarea','size' => '20', 'class' => 'input_big','value'=>$user['User']['interests'])); ?>
<?php echo $form->error('User/interests', 'Email muss angegeben werden'); ?>
</div>

<BR><BR>
<?php
	echo $form->create('Image/images', array('action' => 'add', 'type' => 'file'));
	echo $form->file('File');
?>
<BR><BR>

<?php echo $form->submit('Save'); ?>
</form>
<div class="add_footer">Mit <span class="red">*</span> gekennzeichnete Felder sind unbedingt auszuf&uuml;llen</div>