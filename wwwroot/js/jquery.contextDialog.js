$.fn.contextDialog = function(opts) 
{
	var $button = opts.button;
	var $dialog = $(this);
	$dialog.prepend("<div class='arrowUp'></div>");
	$dialog.addClass('hide');
	var parent = $(this).parent().get(0);
	$button.click(function(e) {
		e.stopPropagation();
		//the next line ensures that:
		// 1. The click comes from somewhere in the parent of the .contextdialog
		// 2. The click may be on the parent itself
		// 3. The click is NOT from within the .contextdialog (there will be other buttons in there)
		if ( (($button.has(e.target).length > 0) || (e.target == $button.get(0))) && ($dialog.has(e.target).length == 0) ) {
			$('div.ContextDialog').addClass('hide'); //only one open at a time
			$dialog.removeClass('hide');
		}
	});
	//$dialog.click(function(e) {
	//	alert('here123');
	//	e.stopPropagation();
	//});
	$('html').click(function(e) {
		if ( ($dialog.has(e.target).length > 0) || ($dialog.get(0) == e.target) ) return;
		$dialog.addClass('hide');
	});
};
