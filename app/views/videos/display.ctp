<?php
// Creo il navigatore.
//$html->addCrumb(__("Home", TRUE), "/");
?>
<div class="homeTitleBox">
    <a href="<? echo $this->webroot ?>videos/">LATEST BOOKMARKED VIDEOS</a> &nbsp;
</div>
<div style="clear: both"></div>
<br>
<?php foreach ($this->requestAction('videos/list_latest/15/') as $Video) { ?>
    <div class="videoBoxElem" style="height: 200px">
        <div style="margin-bottom: 0">
		   <?php
		   // clean blank space around string EM 14.11.2009
		   $Video['Video']['tubecode'] = trim($Video['Video']['tubecode']);
		   $disPicPath                 = str_replace("-", "", $Video['Video']["date"]);
		   $VideoPic                   = $this->webroot . "imgvd/" . $disPicPath . "/pic_" . $Video['Video']["id"] . ".jpg";
		   ?>
        </div>
        <div style="clear: both"></div>
	   <?php $videoY = 'http://img.youtube.com/vi/' . $Video['Video']["tubecode"] . '/default.jpg' ?>
        <div style="border:1px solid white; margin: 0 10px 10px 0; float: left">
            <div class="boxgrid captionfull">
                <A HREF="<? echo $this->webroot . "videos/view/" . $Video['Video']['id'] . "/" . $Video['Video']['bandname']; ?>">
                    <img src="http://img.youtube.com/vi/<?= $Video['Video']['tubecode'] ?>/0.jpg"
                         class="tubevideo okzoomlnk"
                         okimage='http://img.youtube.com/vi/<?= $Video['Video']['tubecode'] ?>/0.jpg' alt="">
                </a>
                <!-- <div class="cover boxcaption">
					<p><?= $Video['Video']['songtitle'] ?><br/></p>
				</div> -->
            </div>
        </div>
        <div style="margin: 0; float: left; ">
            <A HREF="<? echo $this->webroot . "videos/view/" . $Video['Video']['id'] . "/" . $Video['Video']['bandname']; ?>">
			   <? if ($Video['Video']['recom'] == 1) { ?>
                   <img src="<? echo $this->webroot ?>img/heart.png" width=10 alt="">
			   <? } ?>
                <span class="bandname"><?php echo substr($Video['Video']['bandname'], 0, 27); ?></span><br/>
                <p> &#9733 <?= $Video['Video']['songtitle'] ?><br/></p>
            </A><br/><br/>
        </div>
        <div style="clear: both"></div>
    </div>
<?php } ?>
<div style="clear: both"></div>
<div class="homeTitleBox">
    <a href="<? echo $this->webroot ?>videos/topvideos/">TOP BOOKMARKED VIDEOS</a> &nbsp;
</div>
<div style="clear: both"></div>
<BR>
<?php foreach ($this->requestAction('videos/list_last_topvideos/9/') as $Video) { ?>
    <div class="videoBoxElem" style="height: 200px">
        <div style="margin-bottom: 0">
		   <?php
		   // clean blank space around string EM 14.11.2009
		   $Video['Video']['tubecode'] = trim($Video['Video']['tubecode']);
		   $disPicPath                 = str_replace("-", "", $Video['Video']["date"]);
		   $VideoPic                   = $this->webroot . "imgvd/" . $disPicPath . "/pic_" . $Video['Video']["id"] . ".jpg";
		   ?>
        </div>
        <div style="clear: both"></div>
	   <?php
	   //$videoY = 'http://img.youtube.com/vi/'.$Video['Video']["tubecode"].'/default.jpg';
	   $videoY = $this->webroot . "imgvd/missingvideo.jpg";
	   if (
		  //!file_exists(APP."webroot/imgvd/".$disPicPath."/pic_".$Video["id"].".jpg")
	   $Video['Video']["removed"]
	   ) {
		  $VideoPic = $this->webroot . "imgvd/missingvideo.jpg";
	   }
	   ?>
        <div style="border: 1px solid white; margin: 0 10px 10px 0; float: left;">
            <div class="boxgrid captionfull">
                <A HREF="<? echo $this->webroot . "videos/view/" . $Video['Video']['id'] . "/" . $Video['Video']['bandname']; ?>">
                    <img src="http://img.youtube.com/vi/<?= $Video['Video']['tubecode'] ?>/0.jpg"
                         class="tubevideo okzoomlnk"
                         okimage='http://img.youtube.com/vi/<?= $Video['Video']['tubecode'] ?>/0.jpg' alt="">
                </a>
                <!-- <div class="cover boxcaption">
						<p><?= $Video['Video']['songtitle'] ?><br/></p>
					</div> -->
            </div>
        </div>
        <div style="margin: 0; float: left; ">
            <A HREF="<? echo $this->webroot . "videos/view/" . $Video['Video']['id'] . "/" . $Video['Video']['bandname']; ?>">
			   <? if ($Video['Video']['recom'] == 1) { ?>
                   <img src="<? echo $this->webroot ?>img/heart.png" width=10 alt="">
			   <? } ?>
                <span class="bandname"><?php echo substr($Video['Video']['bandname'], 0, 27); ?></span><br/>
                <p> &#9733 <?= $Video['Video']['songtitle'] ?><br/></p>
            </A><br/><br/>
        </div>
        <div style="clear: both"></div>
    </div>
<?php } ?>
<div style="clear: both"></div>
