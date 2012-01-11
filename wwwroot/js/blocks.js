function registerBlocks() {
  $(".block-wrapper").mouseenter(blockEnter);
  $(".block-wrapper").mouseleave(blockExit);
}
function deregisterBlocks() {
  $(".block-wrapper").unbind('mouseenter');
  $(".block-wrapper").unbind('mouseleave');
}

function blockEnter() {
  $(this).addClass("active");
  var blockType = $(this).attr("blockType");
  $edit = $(this).find(".block-menu:first");
  $edit.css("display", "block");
}

function blockExit() {
  $(this).removeClass("active");
  $(this).find(".block-menu").css("display", "none");
}

function blockActionClick(sender, blockId, actionUrl, params) {
  $(sender).basicDialog(blockId, actionUrl, params);
}

/* BLOCK TYPE > TEXT */
function Text_saveTextClick(sender) {
  var $dialog = $(sender).getDialog();
  var $form = $dialog.find("form[name=editTextForm]");
  var params = $form.formToArray();
  getControllerHtml('/block/Text/update', params, function(html, extra) {
    dialogClose($dialog);
  });
}
