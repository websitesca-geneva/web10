Namespace('Web10');

Web10.UploadZone = Class.extend({ 
	
	init: function($div) {
		this.$div = $div;
    var $zone = $div;
    $zone.dropup({
		  url: '/upload.php',
		  params: { 'parentFolderId': 0 },
		  max_file_size: 8,
		  on_complete: function() {
        $zone.removeClass('enterBrowser enterDropzone');
		  },
		  on_start: function($index, $file) {
        var $filediv = $('div.upfile[title="'+$file.name+'"]', $zone);
        var $status = $zone.find('div.status');
        if (! $filediv.length) {
          var $newfile = $('<div class="upfile" title="'+$file.name+'">'+
            $file.name+' ('+$file.size+' bytes): <span>Uploading...<\/span>'+
            '<\/div>');
          $status.append($newfile);
        }
		  },
		  on_progress: function($index, $file, $per) {
        $('div.status [title="'+$file.name+'"] span', $zone).text($per+'%');
      },
      on_finish: function($index, $file, $json) {
        var $filediv = $('div.status [title="'+$file.name+'"]', $zone);
        if ($json.status == 'ERROR') {
          $filediv.addClass('finished error');
          $('div.status [title="'+$file.name+'"] span', $zone).html('<b>'+$json.message+'<\/b>');
        }
        else {
          $filediv.addClass('finished ok');
          $('div.status [title="'+$file.name+'"] span', $zone).html('<b>OK!<\/b>');
        }
      },
      on_g_enter: function() {
        $zone.removeClass('enterBrowser enterDropzone');
        $zone.addClass('enterBrowser');
      },
      on_enter: function() {
        $zone.removeClass('enterBrowser enterDropzone');
        $zone.addClass('enterDropzone');
      },
      on_g_leave: function() {
        $zone.removeClass('enterBrowser enterDropzone');
      }
    });
  }

});
