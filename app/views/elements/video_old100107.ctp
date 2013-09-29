<div class="videoBoxElem" style="">
	<div style="margin-bottom: 0px">
	
	<?php 
	// clean blank space around string EM 14.11.2009
	$Video['tubecode'] = trim($Video['tubecode']);
	?>

	</div>
	<div style="clear: both"></div>
	<div style="border:1px solid white; margin: 0 10px 10px 0; float: left">
		<A HREF="<? echo $this->webroot."videos/view/".$Video['id']."/".$Video['bandname']; ?>">
			<IMG SRC='http://img.youtube.com/vi/<?php echo $Video['tubecode']?>/default.jpg'  width='110' height='80'>
		</A>
	</div>
	<div style="margin: 0px; float: left; ">

		<A HREF="<? echo $this->webroot."videos/view/".$Video['id']."/".$Video['bandname']; ?>">
			<?	if($Video['recom']==1){?>
					<img src="<? echo $this->webroot?>img/heart.png" width=10 border="0">
			<?}?>
			<font style="margin: 0px; color: #333; font: bold 11px arial"><?php echo $Video['bandname'].'- '.$Video['songtitle']; ?></font>
		</A><br /><br />
	
		<a href="<? echo $this->webroot."videos/category/".$Video['category_id']; ?>">
			<? echo $this->requestAction('videos/showcategory/'.$Video['category_id']); ?>
		</a><br />

		Views: <?php echo $Video['views']?> <br />  
		<?php echo sprintf(__("Posted on %s", TRUE),$Video['date']); ?> <br />
		
		by <A HREF="<?=$this->webroot?>users/view/<?=$Video['user_id']?>">
			<?	echo $this->requestAction('users/getuserbyid/'.$Video['user_id']);	?>
			</A>
			
	
		<br />
	</div>
	<div style="clear: both"></div>
	
</div>

