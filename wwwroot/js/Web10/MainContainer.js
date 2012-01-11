Namespace('Web10');

Web10.MainContainer = function() {
	this.ioc = new Ioc();
	this.setupSystemData();
	this.setupPageManager();
	this.setupFileManager();
	this.setupMessaging();
	this.setupModelHelper();
	this.setupBlockDialogs();
};

//pass-through functions
Web10.MainContainer.get = function(id) { return this.ioc.ioc.get(id); };
Web10.MainContainer.factory = function(id, deps) { return this.ioc.ioc.factory(id, deps); };

Web10.MainContainer.prototype.setupBlockDialogs = function() {
	this.setupBlock_MenuDialogView();
};

Web10.MainContainer.prototype.setupBlock_MenuDialogView = function() {
	this.ioc.set('MenuDialogView', function(ioc) {
		return new Web10.Block.Menu.MenuDialogView({
			model: ioc.get('model'),
			pageListTabView: ioc.get('PageListTabView')
		});
	});
};

Web10.MainContainer.prototype.setupSystemData = function() {
	this.ioc.set('pageCol', Web10.Data.pageCol);
	this.ioc.set('layouts', Web10.Data.layouts);
	this.ioc.set('fileCol', Web10.Data.fileCol);
	this.ioc.set('folderCol', Web10.Data.folderCol);
};

Web10.MainContainer.prototype.setupModelHelper = function() {
	this.ioc.set('ModelHelper', function(ioc) {
		return new Web10.Common.ModelHelper({
			messagingModel: ioc.get('MessagingModel')
		});
	});
};

Web10.MainContainer.prototype.setupMessaging = function() {
	this.ioc.set('MessagingModel', this.ioc.singleton(function(ioc) {
		return new Web10.Common.Messaging.MessagingModel();
	}));
	this.ioc.set('MessagingView', function(ioc) {
		return new Web10.Common.Messaging.MessagingView({
				model: ioc.get('MessagingModel')
			});
	});
};

Web10.MainContainer.prototype.setupFileManager = function() {
	this.ioc.set('CurrentFolderModel', this.ioc.singleton(function(ioc) {
		return new Web10.Dialog.FileManager.CurrentFolderModel();
	}));
	this.ioc.set('UploadQueue', function(ioc) {
		return new Web10.Tabs.Upload.UploadQueue();
	});
	this.ioc.set('UploadQueueView', function(ioc) {
		return new Web10.Tabs.Upload.UploadQueueView({
			collection: ioc.get('UploadQueue')
		});
	});
	this.ioc.set('UploadTabView', function(ioc) {
		return new Web10.Tabs.Upload.UploadTabView({
			currentFolderModel: ioc.get('CurrentFolderModel'),
			fileCol: ioc.get('fileCol'),
			uploadQueueView: ioc.get('UploadQueueView')
		});
	});
	this.ioc.set('AddFolderPopdownView', function(ioc) {
		return new Web10.Tabs.FileBrowse.AddFolderPopdownView({
			folderCol: ioc.get('folderCol'),
			currentFolderModel: ioc.get('CurrentFolderModel')
		});
	});
	this.ioc.set('FolderCrumbsView', function(ioc) {
		return new Web10.Common.FolderCrumbs.FolderCrumbsView({
			currentFolderModel: ioc.get('CurrentFolderModel')
		});
	}),
	this.ioc.set('FileBrowseTabView', function(ioc) {
		return new Web10.Tabs.FileBrowse.FileBrowseTabView({
			addFolderPopdownView: ioc.get('AddFolderPopdownView'),
			folderCrumbsView: ioc.get('FolderCrumbsView'),
			currentFolderModel: ioc.get('CurrentFolderModel'),
			folderCol: ioc.get('folderCol'),
			fileCol: ioc.get('fileCol'),
			folderViewCls: Web10.Common.Folder.FolderView,
			fileViewCls: Web10.Common.File.FileView
		});
	});
	this.ioc.set('FileManager', function(ioc) {
		return new Web10.Dialog.FileManager.FileManagerView({
			fileBrowseTabView: ioc.get('FileBrowseTabView'),
			uploadTabView: ioc.get('UploadTabView')
		});
	});
};

Web10.MainContainer.prototype.setupPageManager = function(container) {	
	this.ioc.set('PageViewTable', function(ioc) {
		return new Web10.Tabs.PageList.PageViewTable(ioc.get('pageCol'));
	});
	this.ioc.set('PageListTabView', function(ioc) {
		var view = new Web10.Tabs.PageList.PageListTabView({
			pageCol: ioc.get('pageCol'),
			//pageViewTable: ioc.get('PageViewTable'),
			pageViewCls: Web10.Tabs.PageList.PageView,
			pageEditViewCls: Web10.Tabs.PageList.PageEditView,
			modelHelper: ioc.get('ModelHelper')
		});
		return view;
	});
	this.ioc.set('PageTabView', function(ioc) {
		var view = new Web10.Tabs.Page.PageTabView({
			pageCol: ioc.get('pageCol'),
			layouts: ioc.get('layouts'),
			modelHelper: ioc.get('ModelHelper')
		});
		return view;
	});
	this.ioc.set('PageManager', function(ioc) {
	  var pm = new Web10.Dialog.PageManager.PageManagerView({
	  	layouts: ioc.get('layouts'), 
	  	pageCol: ioc.get('pageCol'),
	  	pageListTabView: ioc.get('PageListTabView'),
	  	pageTabView: ioc.get('PageTabView')
	  });
	  //pm.addTab(new Web10.Tabs.PageList.PageListTabView({pageCol:ioc.get('pageCol')}));
	  //pm.addTab(new Web10.Tabs.Page.PageTabView({pageCol:ioc.get('pageCol'), layouts:ioc.get('layouts')}));
	  return pm;
	});
};
