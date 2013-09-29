$.shortaccesskey = {
  accesskeyArr: Array(),

  init: function () {
    var i = 0;
    $("a[@accesskey]").each (function() {
      $.shortaccesskey.accesskeyArr[i] = $(this).attr('accesskey');
      i++;
    });
  
    $(document).keypress($.shortaccesskey.handleKeyPress);
  
    $("input,textarea,select").focus(function() {
      $(document).unbind();
    });
  
    $("input,textarea,select").blur(function() {
      $(document).keypress($.shortaccesskey.handleKeyPress);
    });
  },

  handleKeyPress: function(e) {
    var e = e || window.event;
    var k = e.charCode ? e.charCode : e.keyCode ? e.keyCode : e.which;
    var keyPressed = String.fromCharCode(k);
    if ($.inArray(keyPressed, $.shortaccesskey.accesskeyArr) >= 0) {
      window.location = $("a[@accesskey="+keyPressed+"]").attr("href");
    }
  }
};

$(document).ready(function(){
    $.shortaccesskey.init();
}
);