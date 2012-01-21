<html>
<head>
<script type='text/javascript' src='/js/core/jquery.js'></script>
<script type='text/javascript' src='/asset/fancybox/jquery.fancybox.js'></script>
<link rel='stylesheet' type='text/css' href='/asset/fancybox/jquery.fancybox.css'>
<script type='text/javascript'>
$(document).ready(function() {

  $('button').click(function(e) {
    //$blah.find('.first').append('another');
    $.fancybox($('#blah1'));
  });
  
})
</script>
</head>
<body>

<button>CLICK</button>

<div id='blah1' style='display:none'><div class='first'>hello</div><div class='second'>secondi</div></div>

</body>
</html>