<?php
// Creo il navigatore.
$html->addCrumb(__("Home", TRUE), "/");
?>

<div style="">
	<div style="float: left; margin: 0 10px 10px 0">
			<img src="<?=$this->webroot?>img/user/<?=$user['User']['image']?>" border=1 width="100">
	</div>

	<div style="float: left; margin: 0 10px 10px 0">
		<h1>Profil von <?php echo $user['User']['name']." ".$user['User']['pastname']; ?></h1>

		<table>
			<tr>
				<td>E-Mail: </td><td><?php echo $user['User']['e-mail']; ?></td>
			</tr>
			<tr>
				<td>Interessen: </td><td><?php echo $user['User']['interests']; ?></td>
			</tr>
			<tr>
				<td>Videos By: </td>
				<td>
					<a href="<?=$this->webroot?>videos/uservideos/<?php echo $user['User']['id']; ?>">
						<?php echo $user['User']['name']." ".$user['User']['pastname']; ?>
					</a>
				</td>
			</tr>
		</table>
	</div>
	<div style="clear: both"></div>
</div>