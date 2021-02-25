<div class="videoBoxElem" style="width: 600px; height: 60px; background: #fff">
    <div style="margin-bottom: 0">
	   <?php
	   // clean blank space around string EM 14.11.2009
	   $Video['tubecode'] = trim($Video['tubecode']);
	   ?>
    </div>
    <div style="border:1px solid white; margin: 0 10px 10px 0; float: left">
        <A HREF="<? echo $this->webroot . "videos/view/" . $Video['id'] . "/" . $Video['bandname']; ?>">
            <IMG SRC='http://img.youtube.com/vi/<?php echo $Video['tubecode'] ?>/default.jpg' width='70'>
        </A>
    </div>
    <div style="margin: 0; float: left; ">
        <A HREF="<? echo $this->webroot . "videos/view/" . $Video['id'] . "/" . $Video['bandname']; ?>">
		   <? if ($Video['recom'] == 1) { ?>
               <img src="<? echo $this->webroot ?>img/heart.png" width=10 alt="">
		   <? } ?>
            <span style="margin: 0; color: #333; font: bold 11px arial"><?php echo $Video['bandname'] . '- ' . $Video['songtitle']; ?></span>
        </A><BR>
        <a href="<? echo $this->webroot . "videos/category/" . $Video['category_id']; ?>">
		   <? echo $this->requestAction('videos/showcategory/' . $Video['category_id']); ?>
        </a>
        Views: <?php echo $Video['views'] ?> <br/>
	   <?php echo sprintf(__("Posted on %s", true), $Video['date']); ?>

        by <A HREF="<?= $this->webroot ?>users/view/<?= $Video['user_id'] ?>">
		  <? echo $this->requestAction('users/getuserbyid/' . $Video['user_id']); ?>
        </A>
        <br/>

        <a href="javascript:void(0);" onClick="AdmDelVid(<?= $Video['id'] ?>)">Delete</a> |
        <a href="javascript:void(0);" onClick="AdmRecoId(<?= $Video['id'] ?>)">Add Recom</a> |
        <a href="javascript:void(0);" onClick="AdmRemRecoId(<?= $Video['id'] ?>)">Rem Recom</a> |
        <a href="javascript:void(0);" onClick="AdmRembyYoutube(<?= $Video['id'] ?>)">Rem by Youtube</a> |
        <a href="javascript:void(0);" onClick="AdmResetYoutube(<?= $Video['id'] ?>)">Reset Youtube</a> |

    </div>
    <div style="clear: both"></div>

</div>

