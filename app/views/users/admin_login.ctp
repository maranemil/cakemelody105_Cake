<h1>Administration</h1>
<div id="login">
<?php 
if($error == "true") {
	echo "<p>Die Login-Informationen konnten nicht verifiziert werden, kontrollieren Sie ihre Daten und versuchen Sie es erneut</p>";
}
?>
<form method="post" action="<?php echo $html->url('/admin/users/login') ?>">
		Username<br />
		<?php echo $html->input('User/username', array('size' => '15', 'class' => 'input_medium')); ?><br /><br />
		Passwort<br />
		<?php echo $html->password('User/password', array('size' => '15', 'class' => 'input_medium')); ?><br />
		<?php echo $html->submit('Anmelden'); ?>
</form>
</div>
<div id="login_foot">
</div>