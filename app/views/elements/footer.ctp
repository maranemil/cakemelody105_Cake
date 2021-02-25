</div>
<!-- / Contenitore Sinistro -->

<!-- Contenitore Destro -->
<div id="contentright">
   <?php if ($session->read("User.username")) { ?>
       <div class="sidebaruser">
           <ul class="sidebaruser">
               <li><a href="<?= $this->webroot ?>videos/step1/<?= $session->read("User.id") ?>">Add Video</a></li>
               <li><a href="<?= $this->webroot ?>users/view/<?= $session->read("User.id") ?>">View My Profile</a></li>
               <li><a href="<?= $this->webroot ?>users/myprofile/<?= $session->read("User.id") ?>">Edit My Profile</a>
               </li>
               <li><a href="<?= $this->webroot ?>videos/uservideos/<?= $session->read("User.id") ?>">My videos</a></li>
               <li><a href="<?= $this->webroot ?>users/changepassword/<?= $session->read("User.id") ?>">Change
                       Password</a></li>
			  <?php if ($session->read("User.rights") == 15) { ?>
                  <li><a href="<?= $this->webroot ?>admins/index/<?= $session->read("User.id") ?>">Administrator
                          Videos</a></li>
			  <?php } ?>
           </ul>
       </div>
   <?php } ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Latest Posts -->
        <ul class="sidebar">
            <li style="background: #F0D000; padding: 10px 0 5px 10px">
                <img src="<?= $this->webroot ?>img/cakemelody.gif" width="214" height="33" alt="">
            </li>
            <li style="" id="downloadbutton">
                <a href="http://code.google.com/p/marancakemelody/" target="_blank" class="downloadbutton">
                    Download cakeMelody 1.03
                </a>
            </li>
            <li><a href="<?= $this->webroot ?>videos/recommended/" id="">Recomended Videos</a></li>
            <li><a href="<?= $this->webroot ?>videos/newvideos" id="">Fresh Videos</a></li>
            <li><a href="<?= $this->webroot ?>videos/topvideos/" id="">Top Videos</a></li>
            <li><a href="<?= $this->webroot ?>videos/" id="">Last Videos</a></li>
        </ul>
    </div>
    <div class="sidebarcat">
        <ul class="sidebarcat">
		   <? //print_r($this->requestAction('/videos/menucategories/'));	?>
		   <?php foreach ($this->requestAction('videos/menucategories/') as $categ): ?>
               <li><a href="<?= $this->webroot ?>videos/category/<?= $categ["categories"]["id"] ?>"
                      id=""><?= $categ["categories"]["name"] ?></a></li>
		   <?php endforeach; ?>
        </ul>
    </div>
    <br/>
    <div id="contentRtTags">
	   <?php
	   foreach ($this->requestAction("/videos/getrandomvideotags/15") as $sVideo) {
		  $arTags = explode(" ", $sVideo["videos"]["tags"]);
		  foreach ($arTags as $sSnTag) {
			 if (strlen($sSnTag) > 4) {
				$myfont = rand(8, 18);
				echo ' <A rel="nofollow" style="font: bold ' . $myfont . 'px arial; margin-right: 10px;" HREF="' . $this->webroot . 'videos/search?searchq=' . trim($sSnTag) . '">' . ucfirst($sSnTag) . '</A> ';
			 }
		  }
	   }
	   ?>
    </div>
    <br/>
    <div id="contentRtTags">
	   <?php
	   /*
	   // Set graph colors
	   $color = array(
				   "#A91F0B",
				   "#C0872D",
				   "#6E1207",
				   "#000000",
				   "#FDC726",
				   "#FF0000",
				   "#FF0033",
				   "#FF3333",
				   "#FF6633",
				   "#FF9933",
				   "#FFCC33",
				   "#FFFF33"
			   );*/
	   ?>

        <h3><a href="<?= $this->webroot ?>videos/statistics">Statistics by Category</a></h3>
	   <?php
	   /*
	   $dataByCategory = $this->requestAction('videos/statistics/5');
	   //print "<pre>"; print_r($dataByCategory); print "</pre>"; die();
	   $chart->setChartAttrs( array(
			   'type' => 'pie', // pie3d
			   'color' => $color,
			   'data' => $dataByCategory,
			   'size' => array(280, 150)
			   ));
		   // Print chart
		   echo "<img src=".$chart->display().">";
		   */
	   ?>
    </div>
    <br/>

   <?php
   //print_r($this->requestAction('users/lastusers/'));
   ?>
    <div id="contentRtUsers">
	   <?php foreach ($this->requestAction('users/lastusers/8') as $user): ?>
           <div class="userBox">
			  <?php if ($user['User']['image']) { ?>
                  <A HREF="<?= $this->webroot ?>videos/uservideos/<?= $user['User']['id'] ?>">
                      <img src="<?= $this->webroot ?>img/user/<?= $user['User']['image'] ?>"  width="71"
                           height="68" alt=""><BR>
					 <?php if ($user['User']['nickname'] == "anonim" && $user['User']['name']) {
						$user['User']['nickname'] = strtolower($user['User']['name']);
					 } ?>
					 <?= $user['User']['nickname'] ?>
                  </A>
			  <?php } else { ?>
                  <A HREF="<?= $this->webroot ?>videos/uservideos/<?= $user['User']['id'] ?>">
                      <img src="<?= $this->webroot ?>img/user/usericon.jpg" width="71" height="68" alt=""><BR>
					 <?php if ($user['User']['nickname'] == "") {
						$user['User']['nickname'] = "me" . $user['User']['id'];
					 } ?>
					 <?= $user['User']['nickname'] ?>
                  </A>
			  <?php } ?>
           </div>
	   <?php endforeach; ?>
        <div style="clear: both"></div>
    </div>
    <!-- / Sidebar -->
</div>
<!-- / Contenitore Destro -->
<div style="clear:both;"></div>
</div>
<!-- / Contenitore -->

<div style="clear:both;"></div>
</div>

<!-- Footer Bar -->
<div id="footerbg">
    <!-- Footer -->
    <div id="footer">
        <!-- Footer Element Left -->
        <div id="footerleft">
            <H1>Cakephp Webs</H1><br/>
            <ul>
                <li><a href="http://www.cakephp.org/" target="_blank">www.cakephp.org</a></li>
                <li><a href="http://cakeforge.org/" target="_blank">cakeforge.org</a></li>
                <li><a href="http://book.cakephp.org/" target="_blank">book.cakephp.org</a></li>
                <li><a href="http://bakery.cakephp.org/" target="_blank">bakery.cakephp.org</a></li>
            </ul>
        </div>
        <!-- / Footer Element Left -->

        <!-- Blogroll -->
        <div id="footermiddle">
            <H1>Related Webs</H1><br/>
            <ul>
                <li><a href="http://www.maran-emil.de/" target="_blank">www.maran-emil.de</a></li>
                <li><a href="http://www.imagine-things.com/" target="_blank">www.imagine-things.com</a></li>
                <li> &nbsp;</li>
                <li> &nbsp;</li>
                <li><a href="http://code.google.com/" target="_blank">code.google.com</a></li>
                <li><a href="https://github.com/" target="_blank">github.com</a></li>
                <li><a href="http://www.hotscripts.com/" target="_blank">www.hotscripts.com</a></li>
                <li> &nbsp;</li>
                <li> &nbsp;</li>
                <li><a href="http://www.search-scripts.com/" target="_blank">www.search-scripts.com</a></li>
                <li><a href="http://www.youtube.com/" target="_blank">www.youtube.com</a></li>
                <li><a href="http://www.kewego.de/" target="_blank">www.kewego.de</a></li>
            </ul>
        </div>
        <!-- / Blogroll -->

        <!-- Footer Element Right -->
        <div id="footerright">
            <!-- Credits -->
            <H1>Credits</H1>
            <p>
                Developed with <?php echo $html->link("Emil Maran", "http://www.maran-emil.de"); ?>.<br/>
                Copyright&copy; <?php echo date("Y", time()); ?> Emil Maran.<br/>
                Idea is based
                on <?php echo $html->link("Pereira Pulido Nuno Ricardo Design", "http://www.namaless.com"); ?>.<br/>
			   <?php echo $html->link("xHTML", "http://validator.w3.org/check/referer", array('title' => "This page validates as XHTML 1.0 Transitional")); ?>
                | <?php echo $html->link("CSS", "http://jigsaw.w3.org/css-validator/check/referer", array('title' => "This page validates as CSS")); ?>
            </p>
            <!-- / Credits -->
		   <?php //echo $html->image("card.gif"); ?>
        </div>
        <!-- / Footer Element Right -->
    </div>
    <div style="clear: both"></div>
    <!-- / Footer -->
</div>
<!-- / Footer Bar -->

<?php
//echo $cakeDebug;
?>
<script type="text/javascript">
	/*
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-16984232-3']);
		_gaq.push(['_trackPageview']);

		(function () {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();
	*/
</script>
</body>
</html>


