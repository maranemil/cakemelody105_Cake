<div style="border:1px solid white; margin-bottom: 0px">
	<div style="float: left; margin: 0 10px 10px 0">
		<img src="img/user/<?=$User['image']?>" border=1 width="50">
	</div>
	<div style="float: left; margin: 0 0 0 0px">
	<h1><?php echo $html->link($User['name']." ".$User['pastname'],"/users/view/".$User['id']."/".$User['name']); ?></h1>
	</div>
	<div style="clear: both"></div>
</div>