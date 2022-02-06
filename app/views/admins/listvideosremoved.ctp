<script>
    function AdmDelVid(id) {
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>admins/deletevideo/" + id + "",
            data: "",
            success: function (msg) {
                alert("Deleted!");
            }
        });
        //	setTimeout(function() { $("#imgbox_"+id).html("<a style='color: white; border: 0px none;' href='"+url+"' target='_new'><img src='<?=$this->webroot?>imgbk/pic_thumb"+id+".jpg'></a>").fadeIn(); },	randomGet);
        //$("#imgbox_"+id).html("<img src='<?=$this->webroot?>img/ajax-loader-new1.gif' style='margin: 50px 0 0 30px'>");
    }

    function AdmRecoId(id) {
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>admins/addrecomendedvideo/" + id + "",
            data: "",
            success: function (msg) {
                alert("Added as recommended!");
            }
        });
    }

    function AdmRemRecoId(id) {
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>admins/remrecomendedvideo/" + id + "",
            data: "",
            success: function (msg) {
                alert("Removed as recommended!");
            }
        });
    }

    function AdmRembyYoutube(id) {
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>admins/removedbyyoutube/" + id + "",
            data: "",
            success: function (msg) {
                alert("OK! Set as removed!");
            }
        });
    }

    function AdmResetYoutube(id) {
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>admins/resetbyyoutube/" + id + "",
            data: "",
            success: function (msg) {
                alert("OK! Reset video!");
            }
        });
    }

</script>
<?php foreach ($arTmpVid as $sVid): ?>
    <?php echo $this->renderElement("adminvideo", $sVid); ?>
<?php endforeach; ?>
<?php //print_r($arTmpUsr)?>
<?php echo $this->renderElement('pagination', $paging); ?>
