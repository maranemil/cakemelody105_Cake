<?php $html->addCrumb(__("Home", TRUE), "/"); ?>
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

//http://docs.jquery.com/Ajax/jQuery.ajax
//http://docs.jquery.com/Ajax


    }

    function AdmRecoId(id) {
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>admins/addrecomendedvideo/" + id + "",
            data: "",
            success: function (msg) {
                //alert( "Deleted!");
            }
        });
    }

    function RemRecoId(id) {
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>admins/remrecomendedvideo/" + id + "",
            data: "",
            success: function (msg) {
                // alert( "Deleted!");
            }
        });
    }
</script>


<?php foreach ($arTmpVid as $sVid): ?>
    <?php echo $this->renderElement("adminvideo", $sVid); ?>
<?php endforeach; ?>
<?php //print_r($arTmpUsr)?>
<?php echo $this->renderElement('pagination', $paging); ?>
