Namespace('Web10');

Web10.Editing = function(ioc) {
	this.ioc = ioc;
};

Web10.Editing.prototype.init = function() {
	this.initToolbar(this.ioc);
	this.initMessaging(this.ioc);
	this.bindBlocksToModels(this.ioc);
};
		
Web10.Editing.prototype.bindBlocksToModels = function() {
	$('.block').each(function(i, block) {
		var blockid = $(block).attr('blockid');
		var model = Web10.Data.blockModel[blockid];
		//need to use wrapper to find the block to replace because on the 
		// second time this is called, block is a different block
		var $wrapper = $(block).closest('.block-wrapper');
		model.bindings(Web10.Data, function() {
			var url = '/dispatch.php?dt=blockview&id='+blockid+'&t='+model.get('blocktype');
			$.get(url, null, function(data) {
				$wrapper.find('.block').replaceWith(data);
			});
		});
	});
};

Web10.Editing.prototype.initMessaging = function(ioc) {
	var view = ioc.get('MessagingView');
	$("body").append(view.render().el);
};
	
Web10.Editing.prototype.initToolbar = function(ioc) {
	this.setupPageManager(ioc);
	this.setupFileManager(ioc);
	this.setupEditMode(ioc);
};

Web10.Editing.prototype.setupFileManager = function(ioc) {
	var self = this;
	$('#toolbar #fileManagerButton').click(function(e) {
		if (! self.fileManager) {
			//var m = new Web10.Dialogs.FileManager.FileManagerModel;
			//var o = {
			//	fileCol: Web10.Data.fileCol,
			//	folderCol: Web10.Data.folderCol,
			//	currentFolderModel: new Web10.Dialog.FileManager.CurrentFolderModel
			//};
			//Web10.Editing.fileManager = new Web10.Dialog.FileManager.FileManagerView(o);
			self.fileManager = ioc.get('FileManager');
		  $("body").append(self.fileManager.render().el);
		}
		else {
			self.fileManager.open();
		}
	});
};
	
Web10.Editing.prototype.setupPageManager = function(ioc) {
	var self = this;
	$('#toolbar #pageManagerButton').click(function(e) {
		if (! Web10.Editing.pageManager) {
			//var o = {layouts:Web10.Data.layouts, pageCol:Web10.Data.pageCol};
			//Web10.Editing.pageManager = new Web10.Dialog.PageManager.PageManagerView(o);
			self.pageManager = ioc.get('PageManager');
		  $("body").append(self.pageManager.render().el);
		}
		else {
			self.pageManager.toggleOpen();
		}
	});
};

Web10.Editing.prototype.setEditMode = function(isEditingOn) {
  var editMode = (isEditingOn) ? "on" : "off";
  $.cookie("EditMode", editMode);
  if (isEditingOn) {
    $("#toolbar #editingMode a[name=off]").attr("class", "");
    $("#toolbar #editingMode a[name=on]").addClass("selected");
    this.registerBlocks();
  } else {
    $("#toolbar #editingMode a[name=on]").attr("class", "");
    $("#toolbar #editingMode a[name=off]").addClass("selected");
    this.deregisterBlocks();
  }
};
	
Web10.Editing.prototype.registerBlocks = function() {
  $(".block-wrapper").mouseenter(this.blockEnter);
  $(".block-wrapper").mouseleave(this.blockExit);
  $(".block-wrapper .block-menu a[name=edit]").click($.proxy(this.blockEdit, this));
};
	
Web10.Editing.prototype.deregisterBlocks = function() {
  $(".block-wrapper").unbind('mouseenter');
  $(".block-wrapper").unbind('mouseleave');
  $(".block-wrapper .block-menu a[name=edit]").unbind('click');
};
	
Web10.Editing.prototype.blockEdit = function(e) {
	var ioc = Web10.ioc;
	var $block = $(e.target).closest('.block-wrapper').find('.block');
	var blockid = $block.attr('blockid');
	var dialog = $block.data('dialog');
	if (dialog == null) {
		var blockModel = Web10.Data.blockModel[blockid];
		var blocktype = blockModel.get('blocktype');
		var factory = ioc.get(blocktype+'DialogViewFactory');
		var dialog = factory.get({
			blockType: blocktype,
			blockModel: blockModel,
			parent: $block
		});
		
		//var viewCls = Web10.Block[blocktype][blocktype+'DialogView'];
		//var dialog = new viewCls({model:model, sysdata:Web10.Data});
		
		$block.data('dialog', dialog);
		$("body").append(dialog.render().el);
		
		dialog.open();
	}
	else {
		dialog.open();
	}
};

Web10.Editing.prototype.blockEnter = function() {
  $(this).addClass("active");
};

Web10.Editing.prototype.blockExit = function() {
  $(this).removeClass("active");
};

Web10.Editing.prototype.setupEditMode = function() {
  var editMode = $.cookie("EditMode");
  var self = this;
  this.setEditMode((editMode=="on") ? true : false);
  $("#toolbar #editingMode a[name=on]").click(function() {
  	self.setEditMode(true);
  });
  $("#toolbar #editingMode a[name=off]").click(function() {
  	self.setEditMode(false);
  });
};
