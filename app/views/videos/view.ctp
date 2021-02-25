<?php //echo $this->renderElement('post',$post); ?>
<?php // echo "<pre>"; print_r($Video); echo "</pre>"; ?>
<script>
	function saveRating(ratingval, videoid) {
		$.ajax({
			type: "GET",
			url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>videos/ratingsave/" + ratingval + "/" + videoid + "",
			data: "",
			success: function (msg) {
				//alert( "Saved!");
			}
		});
//http://docs.jquery.com/Ajax/jQuery.ajax
//http://docs.jquery.com/Ajax
	}
</script>
<?php
// clean blank space around string - EM 14.11.2009
$Video[0]['videos']['tubecode'] = trim($Video[0]['videos']['tubecode']);
$arTmpBName                     = explode(" ", $Video[0]['videos']['bandname']);
$arTmpSName                     = explode(" ", $Video[0]['videos']['songtitle']);
?>
<div style="width: 640px; float: left; padding: 0 5px 5px 5px">
    <h1 style="margin: 0; color: white"><?= $Video[0]['videos']['bandname'] ?>
        - <?= $Video[0]['videos']['songtitle'] ?></h1>
    <div style="width: 630px; ">
        <!-- layerFlash  -->
        <a id="layerFlashCnt"></a>
        <div id="layerFlash">
            <object width="630" height="376">
                <param name="movie"
                       value="http://www.youtube.com/v/<?= $Video[0]['videos']['tubecode'] ?>&hl=en">
                <param name="wmode" value="transparent">
                <embed src="http://www.youtube.com/v/<?= $Video[0]['videos']['tubecode'] ?>&hl=en"
                       type="application/x-shockwave-flash" wmode="transparent" width="630" height="376"/>
            </object>
        </div>
        <!-- layerFlash / -->
        <HR>
        <h3 class="videoInfoBox">
		   <? echo $this->renderElement('rating', array('rating' => $rating, 'votes' => $votes)); ?>
            Views : <?= $Video[0]['videos']['views'] ?> <br/>
            Videos from
            <A HREF="<? echo $this->webroot . "videos/uservideos/" . $Video[0]['videos']['user_id']; ?>">
                ( <? echo $this->requestAction('users/getuserbyid/' . $Video[0]['videos']['user_id']); ?> )
            </a><BR>
            Youtube <A HREF="http://www.youtube.com/watch?v=<?= $Video[0]['videos']['tubecode'] ?>" target="_blank">(
                source )</A>
            <BR>
        </h3>
        <div style="clear: both"></div>
    </div>
    <br><br>
    <!--  -->
    <script>
		//$(document).ready(function(){
		$.getJSON('http://gdata.youtube.com/feeds/api/videos?q=<?=trim($arTmpBName[0])?> - <?=$arTmpSName[0]?>&alt=json-in-script&callback=?&max-results=20&start-index=1', function (data) {

			$.each(data.feed.entry, function (i, item) {
				const title = item['title']['$t'];
				let video = item['id']['$t'];

				video = video.replace('http://gdata.youtube.com/feeds/api/videos/', 'http://www.youtube.com/watch?v=');  //replacement of link
				const videoID = video.replace('http://www.youtube.com/watch?v=', '');
				// removing link and getting the video ID
                // alert(title);
				//$('#BoxVidAlternativ').append('<a href="'+video+'"> '+title+'</a> -'+videoID+'<br/> ');
				//$('#BoxVidAlternativ').append('<a href="'+video+'"><img src="http://img.youtube.com/vi/'+videoID+'/default.jpg"></a> ');

				let DinVidItem = '<div id="videoBoxElemAlt" style="">';
				DinVidItem += '<div style="margin-bottom: 0">';
				DinVidItem += '</div>';
				DinVidItem += '<div style="clear: both"></div>';

				DinVidItem += '<div style="border:1px solid white; margin: 0 10px 10px 0; float: left">';
				DinVidItem += '<a href="javascript:void(0)" onclick="replaceContentFlash(\'' + videoID + '\')"><img src="http://img.youtube.com/vi/' + videoID + '/default.jpg"></a>';
				DinVidItem += '</div>';
				DinVidItem += '<div style="margin: 0; float: left; ">';
				//DinVidItem +='<a href="'+video+'"';
				DinVidItem += '<a href="javascript:void(0)" onclick="replaceContentFlash(\'' + videoID + '\')">';
				DinVidItem += '<font style="margin: 0; color: #333; font: bold 11px arial">' + title.substr(0, 65) + '</font>';
				DinVidItem += '</a><br /><br />';
				DinVidItem += '';
				DinVidItem += '<br />';
				DinVidItem += '<br />';
				DinVidItem += '</div>';
				DinVidItem += '<div style="clear: both"></div>';

				$('#BoxVidAlternativ').append(DinVidItem);
			});
		});

		//});

		function replaceContentFlash(videoID) {

			let VidRepItem = '<object width="630" height="376">';
			VidRepItem += '<param name="movie" value="http://www.youtube.com/v/' + videoID + '&hl=en"></param>';
			VidRepItem += '<param name="wmode" value="transparent"></param>';
			VidRepItem += '<embed src="http://www.youtube.com/v/' + videoID + '&hl=en" type="application/x-shockwave-flash" wmode="transparent" width="630" height="376"/>';
			VidRepItem += '</object>';

			$("#layerFlash").html(VidRepItem);
			$('html, body').animate({scrollTop: $("#layerFlashCnt").offset().top}, 500);
		}
    </script>
    <style>
        #BoxVidAlternativ {
            width: 650px;
            /*height: 600px;*/
            color: #333;
        }

        #BoxVidAlternativ a {
            color: #333;
        }

        #videoBoxElemAlt {
            /*background:none repeat scroll 0 0 #FAF301;*/
            border: 1px solid #888888;
            float: left;
            height: 170px;
            margin-bottom: 10px;
            margin-right: 10px;
            padding: 10px;
            width: 128px;
            background: #FAF301;
            background: -moz-linear-gradient(center top, #FEB813, #FFFFFF) repeat scroll 0 0 transparent;
        }
    </style>
    <div id="BoxVidAlternativ"></div>
    <div style="clear: both"></div>
    <!--  -->
   <?php
   if ($test) {
	  foreach ($this->requestAction('videos/getrelatedvideos/' . $Video[0]['videos']['bandname']) as $relVideo) {
		 //echo $this->renderElement("videorelated", $relVideo);
		 //echo "<img src='http://img.youtube.com/vi/".$relVideo["videos"]["tubecode"]."/default.jpg'>";
		 ?>
          <div class="videoBoxElem" style="">
              <div style="margin-bottom: 0px">
				 <?php
				 // clean blank space around string EM 14.11.2009
				 $relVideo["videos"]['tubecode'] = trim($relVideo["videos"]['tubecode']);
				 ?>
              </div>
              <div style="clear: both"></div>
			 <?php $videoY = 'http://img.youtube.com/vi/' . $relVideo["videos"]["tubecode"] . '/default.jpg' ?>
			 <?php if (!file_exists(APP . "webroot/imgvd/pic_thumb" . $relVideo["videos"]["id"] . ".jpg")) {
				?>

				<?php
			 } ?>
              <div style="border:1px solid white; margin: 0 10px 10px 0; float: left">
                  <img src='http://img.youtube.com/vi/<?= $relVideo["videos"]["tubecode"] ?>/default.jpg' alt="">
              </div>
              <div style="margin: 0; float: left; ">
                  <A HREF="<?php echo $this->webroot . "videos/view/" . $relVideo["videos"]['id'] . "/" . $relVideo["videos"]['bandname']; ?>">
					 <?php if ($relVideo["videos"]['recom'] == 1) { ?>
                         <img src="<? echo $this->webroot ?>img/heart.png" width=10>
						<?php
					 } ?>
                      <span style="margin: 0; color: #333; font: bold 11px arial"><?php echo $relVideo["videos"]['bandname'] . ' - ' . substr($relVideo["videos"]['songtitle'], 0, 25); ?>
                          ...</span>
                  </A><br/><br/>
                  <a href="<? echo $this->webroot . "videos/category/" . $relVideo["videos"]['category_id']; ?>">
					 <? echo $this->requestAction('videos/showcategory/' . $relVideo["videos"]['category_id']); ?>
                  </a><br/>
                  Views: <?php echo $relVideo["videos"]['views'] ?> <br/>
				 <?php echo sprintf(__("Posted on %s", true), $relVideo["videos"]['date']); ?> <br/>
                  by <A HREF="<?= $this->webroot ?>users/view/<?= $relVideo["videos"]['user_id'] ?>">
					<? echo $this->requestAction('users/getuserbyid/' . $relVideo["videos"]['user_id']); ?>
                  </A>
                  <br/>
              </div>
              <div style="clear: both"></div>
          </div>
		 <?
	  }
   }
   ?>
</div>
<!-- / Comments Area -->
