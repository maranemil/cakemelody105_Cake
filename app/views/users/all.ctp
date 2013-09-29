<h1>
<?php echo $html->image("tango/64x64/apps/system-users.png", array('alt' => 'User')); ?>
Alle Mitglieder</h1>
<table border="0">
<tr class="first_line"><td>Online</td><td>Username</td><td>Blog</td></tr>
<?php foreach ($users as $user): ?>
<tr>
	<td>
		<?php
		if($user['User']['online'] == "true") {
			echo $html->image('tango/22x22/status/weather-clear.png', array('alt' => 'Online'));
		}
		else {
			echo $html->image('tango/22x22/status/weather-clear-night.png', array('alt' => 'Offline'));
		}
		?>
	</td>
	<td>
		<?php echo $user['User']['username'] ?>
		<a href="<?php echo $html->url('/messages/add/'); echo $user['User']['username']; ?>"><?php echo $html->image('tango/16x16/actions/mail-message-new.png', array('alt' => 'Neue Nachricht', 'border' => '0')); ?>
	</td>
	<td>
		<?php echo $html->link('Blog von '.$user['User']['username'], '/posts/blog/'.$user['User']['id']); ?>
	</td>
	<td><?php echo $html->link('Info', '/users/view/'.$user['User']['id']); ?></td>
</tr>
<?php endforeach; ?>
</table>