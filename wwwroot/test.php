<?php
require_once 'Web10/Common/CoreClassLoader.php';
$loader = new Web10\Common\CoreClassLoader();
$loader->setup();

use Web10\Common\CoreContainer;
use AssetManager\JsDataAsset;

$c = new CoreContainer();
$ac = $c->get('Web10\Common\Contexts\AccountContext');
$ac->setupByHostname('localhost');
$c['Web10\Common\Contexts\AccountContext'] = $ac;

$fm = $c->get('Web10\Business\FileManager');
$files = $fm->getAllFiles();

$helper = $c->get('Web10\Web\WebsiteDefHelper');
$assets = $c->get('AssetManager\AssetManager');

$imageGridBlockManager = $c->get('Web10\Business\ImageGridBlockManager');
$block = $imageGridBlockManager->getImageGridBlock(9);
$blockType = 'Web10.Domain.Block.ImageGrid';
$assets->addAsset(new JsDataAsset('imageGridBlock', $block, $blockType));
?>

<html>
<head>
<script type='text/javascript' src='/js/jquery.js'></script>
<script type='text/javascript' src='/js/jquery.contextDialog.js'></script>
<script type='text/javascript' src='/js/ui/jquery-ui-1.8.14.custom.js'></script>
<script type='text/javascript' src='/js/Namespace.js'></script>
<script type='text/javascript' src='/js/mustache.js'></script>
<script type='text/javascript' src='/js/Underscore.js'></script>
<script type='text/javascript' src='/js/Backbone.js'></script>
<script type='text/javascript' src='/js/ioc.js'></script>
<script type='text/javascript' src='/js/Web10/Dialog/TabbedDialog.js'></script>
<script type='text/javascript' src='/js/Web10/Tabs/BaseTabView.js'></script>

<script type='text/javascript' src='/js/Web10/ContainerReg/Main.js'></script>
<script type='text/javascript' src='/js/Web10/ContainerReg/Block.js'></script>

<script type='text/javascript' src='/js/Web10/Common/ModelHelper.js'></script>
<script type='text/javascript' src='/js/Web10/Common/Messaging/MessagingModel.js'></script>

<script type='text/javascript' src='/js/Web10/Domain/PageList.js'></script>
<script type='text/javascript' src='/js/Web10/Domain/FileList.js'></script>
<script type='text/javascript' src='/js/Web10/Domain/FolderList.js'></script>
<script type='text/javascript' src='/js/Web10/Domain/Block/ImageGrid.js'></script>
<script type='text/javascript' src='/js/Web10/Domain/SelectedFileList.js'></script>

<script type='text/javascript' src='/js/Web10/Tabs/SelectedFiles/SelectedFileView.js'></script>

<script type='text/javascript' src='/js/Class.js'></script>
<script type='text/javascript' src='/js/Web10/UploadZone.js'></script>

<link rel='stylesheet' href='/js/Web10/Dialog/TabbedDialog.css'>

<link rel='stylesheet' href='/js/Web10/Tabs/SelectedFiles/SelectedFilesTabView.css'>

<link rel='stylesheet' href='/js/Web10/Tabs/SelectedFiles/SelectedFileView.css'>

<style>
.list {
  width: 200px;
  background-color: #555;
  padding: 20px;
}
.list .item {
  background-color: #999;
  padding: 10px;
  margin: 5px;
  float: left;
  font-size: 200%;
}
.list .item:hover {
  cursor: move;
}
</style>
<script type='text/javascript'>
<?= $helper->getJsDataScript(); ?>

$(document).ready(function() {

  var ioc = new Ioc();
  ioc.register(Web10.ContainerReg);
  Web10.ioc = ioc;

  var tab = Web10.Tabs.BaseTabView.extend({
  	
  	className: 'tab SelectedFilesTabView',
  	
  	//listTemplate: "hello:<ol class='list'><li class='item'>1</li><li class='item'>2</li><li class='item'>3</li><li class='item'>4</li><li class='item'>5</li><li class='item'>6</li><li class='item'>7</li><li class='item'>8</li><li class='item'>9</li><div style='clear:both;'></div></ol>",

  	listTemplate: "the list: <ol class='fileList'></ol><div class='after'></div>",
    
  	initialize: function(opts) {
  	  Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
  	  _.bindAll(this, 'render');
      this.fileCol = ioc.get('fileCol');
      this.block = Web10.Data.imageGridBlock;
      this.selected = new Web10.Domain.SelectedFileList;
      this.selectedFileViewFactory = ioc.get('SelectedFileViewFactory');
  	  this.title = 'sortable';

      for (i in this.block.get('images')) {
        var img = this.fileCol.getModelById(this.block.get('images')[i].imageId);
        this.selected.select(img);
      }
  	},
  	
  	render: function() {
      console.log('in this render');
      $(this.el).html(this.listTemplate);
      var self = this;
      this.selected.each(function(fileModel) {
		//var view = self.selectedFileViewFactory.get({fileModel:fileModel});
        var view = new Web10.Tabs.SelectedFiles.SelectedFileView({
          modelHelper: ioc.get('ModelHelper'),
          selectedModel: self.selected,
          fileModel: fileModel
        });
    
		var $li = $("<li></li>");
		$li.append(view.render().el);
		$(".fileList", self.el).append($li);
	  });
      this.$("ol.fileList").sortable();
      this.$("ol.fileList").disableSelection();
      return this;
  	}
  	
  });
  
  var dialog = new Web10.Dialog.TabbedDialog;
  dialog.addTab(new tab);
  dialog.title = 'blah';

  $('body').append(dialog.render().el);

  //$("ol.list").sortable();
  //$("ol.list").disableSelection();
  
});

</script>
</head>
<body>

hello

<!--
<ol class='list'><li class='item'>1</li><li class='item'>2</li><li class='item'>3</li><li class='item'>4</li><li class='item'>5</li><li class='item'>6</li><li class='item'>7</li><li class='item'>8</li><li class='item'>9</li><div style='clear:both;'></div></ol>
-->

</body>
</html>
