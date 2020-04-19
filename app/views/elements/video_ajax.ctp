<script>
    function createThumb(image, id, folder) {
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?><?=$this->webroot?>videos/copyimagetolocal/?imgpath=" + image + "&pic_width=150&pic_height=300&idpic=" + id + "&folder=" + folder,
            data: "",
            success: function (msg) {
                //alert( "Saved!");
            }
        });
        //http://docs.jquery.com/Ajax/jQuery.ajax
        //http://docs.jquery.com/Ajax
    }

    function getThumb(image, id, folder, hasimg) {

        if (hasimg === 1) {
            let randomGet = 1000 + Math.floor(Math.random() * 1000);
            setTimeout(function () {
                    $("#imgdiv_" + id).html("<a href='<?=$this->webroot?>videos/view/" + id + "/'><img src='<?=$this->webroot?>imgvd/" + folder + "/pic_" + id + ".jpg'></a>").fadeIn("slow");
                },
                randomGet);
        } else if (hasimg === 0) {
            let randomGet = 1000 + Math.floor(Math.random() * 1000);
            setTimeout(function () {
                    $("#imgdiv_" + id).html("<a href='<?=$this->webroot?>videos/view/" + id + "/'><img src='<?=$this->webroot?>imgvd/missingvideo.jpg'></a>").fadeIn("slow");
                },
                randomGet);
        }

        //$("#imgbox_"+id).html("<a href='"+image+"' target='_new'><img src='<?=$this->webroot?>imgbk/pic_thumb"+id+".jpg'></a>");
        //http://docs.jquery.com/Manipulation
        //setTimeout(function() { $('#foo').fadeOut(); }, 5000);

    }

    function getLoader(image, id) {
        $("#imgdiv_" + id).html("<img src='<?=$this->webroot?>img/ajax-loader-new1.gif' style='margin: 10px 0 0 30px'>");
    }
    // setTimeout(function() { $('#foo').fadeOut(); }, 5000);
</script>
