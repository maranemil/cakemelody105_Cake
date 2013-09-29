<div class="videoBoxElem" style="">
	<div style="margin-bottom: 0px">

	<?php 
	// clean blank space around string EM 14.11.2009
	$Video['tubecode'] = trim($Video['tubecode']);
	?>

	</div>
	<div style="clear: both"></div>
	<? 
	$videoY = 'http://img.youtube.com/vi/'.$Video["tubecode"].'/default.jpg';
	$disPicPath = str_replace("-","",$Video["date"]);
	$VideoPic = $this->webroot."imgvd/".$disPicPath."pic_".$Video["id"].".jpg";
	$VideoPicDef = $this->webroot."imgvd/missingvideo.jpg";
	$hasimg = $Video['hasimg'];
	$hasimg = 1;
	?>

	<?
		if(
			//!file_exists(APP."webroot/imgvd/".$disPicPath."/pic_".$Video["id"].".jpg")
			filesize(APP."webroot/imgvd/".$disPicPath."/pic_".$Video["id"].".jpg") < 250
		
		){
			$this->requestAction('videos/removedbyyoutube/'.$Video['id']);
			$hasimg = 0;
			
		?>
		<script>  
			//createThumb('<?=$videoY ?>',<?=$Video["id"]?>,<?=$disPicPath?>); 
		</script>
	<?}?>

		<div style="border:1px solid white; margin: 0 10px 10px 0; float: left">
			<div class="boxgrid captionfull">
				<A HREF="<? echo $this->webroot."videos/view/".$Video['id']."/".$Video['bandname']; ?>">
					<img src="http://img.youtube.com/vi/<?=$Video['tubecode']?>/0.jpg" class="tubevideo okzoomlnk" okimage='http://img.youtube.com/vi/<?=$Video['tubecode']?>/0.jpg'>
				</a>
				<!-- <div class="cover boxcaption">
					<p><?=$Video['songtitle']?><br/></p>
				</div> -->
			</div>
		</div>

		<script> //getLoader('<?=$videoY ?>',<?=$Video['id']?>); </script>
		<script> 
			//getThumb('<?=$videoY ?>',<?=$Video['id']?>,<?=$disPicPath?>,<?=$hasimg?>);
		</script>

		<div style="margin: 0px; float: left; ">
			<A HREF="<? echo $this->webroot."videos/view/".$Video['id']."/".$Video['bandname']; ?>">
				<?	if($Video['recom']==1){?>
						<img src="<? echo $this->webroot?>img/heart.png" width=10 border="0">
				<?}?>
				<span class="bandname"><?php echo $Video['bandname']?></span>
			</A><br />
			 &#9733 <?=$Video['songtitle']?><hr />
			<!--  -->

			<a href="<? echo $this->webroot."videos/category/".$Video['category_id']; ?>">
				<? echo $this->requestAction('videos/showcategory/'.$Video['category_id']); ?>
			</a> <br />

			Views: <?php echo $Video['views']?> <br />  
			<?php // echo sprintf(__("Date %s", TRUE),$Video['date']); ?> 
			<?php  echo $Video['date']; ?> /
			
			<A HREF="<?=$this->webroot?>users/view/<?=$Video['user_id']?>">
				<?	echo $this->requestAction('users/getuserbyid/'.$Video['user_id']);	?>
				</A>
		<br />
	</div>
	<div style="clear: both"></div>
	
</div>

