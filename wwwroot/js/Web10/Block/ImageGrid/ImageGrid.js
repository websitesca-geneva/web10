$(document).ready(function() {
  $(".block.ImageGrid a.fancybox").fancybox({
    arrows: true,
    nextClick: true,
    loop: true,
    
    prevEffect  : 'none',
    nextEffect  : 'none',
    helpers : {
      title : {
        type: 'outside'
      },
      overlay : {
        opacity : 0.8,
        css : {
          'background-color' : '#000'
        }
      }
    }
    
  });
});
