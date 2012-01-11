Namespace('Web10.ContainerReg');

Web10.ContainerReg.Main = function(ioc) {
	this.ioc = ioc;
}

Web10.ContainerReg.Main.prototype.register = function() {
	this.setupSystemData();
	this.setupDomain();
	this.setupPageManager();
	this.setupFileManager();
	//this.setupFileManager_SelectSingleImage();
	this.setupMessaging();
	this.setupModelHelper();
}

Web10.ContainerReg.Main.prototype.setupSystemData = function() {
	this.ioc.set('sysdata', Web10.Data);
	this.ioc.set('pageCol', Web10.Data.pageCol);
	this.ioc.set('layouts', Web10.Data.layouts);
	this.ioc.set('fileCol', Web10.Data.fileCol);
	this.ioc.set('folderCol', Web10.Data.folderCol);
}

Web10.ContainerReg.Main.prototype.setupDomain = function() {
	
}

Web10.ContainerReg.Main.prototype.setupModelHelper = function() {
	this.ioc.set('ModelHelper', function(ioc) {
		return new Web10.Common.ModelHelper({
			messagingModel: ioc.get('MessagingModel')
		});
	});
};

Web10.ContainerReg.Main.prototype.setupMessaging = function() {
	this.ioc.set('MessagingModel', this.ioc.singleton(function(ioc) {
		return new Web10.Common.Messaging.MessagingModel();
	}));
	this.ioc.set('MessagingView', function(ioc) {
		return new Web10.Common.Messaging.MessagingView({
				model: ioc.get('MessagingModel')
			});
	});
};

Web10.ContainerReg.Main.prototype.setupFileManagers = function() {
	this.ioc.set('FileManagers', this.ioc.singleton(function(ioc) {
		return ioc.child();
	}));
}

/*
Web10.ContainerReg.Main.prototype.setupFileManager_SelectSingleImage = function() {
	var scope = this.ioc.get('FileManagers');
	scope.set('SelectedModel', scope.singleton(function(ioc) {
		return new Web10.Domain.SelectedFileSingle();
	}));
	scope.set('FileBrowseTabView', function(ioc) {
		return new Web10.Tabs.FileBrowse.FileBrowseTabView({
			//addFolderPopdownView: ioc.get('AddFolderPopdownView'),
			modelHelper: ioc.get('ModelHelper'),
			folderCrumbsView: ioc.get('FolderCrumbsView'),
			currentFolderModel: ioc.get('CurrentFolderModel'),
			folderCol: ioc.get('folderCol'),
			fileCol: ioc.get('fileCol'),
			folderViewFactory: new RuntimeFactory(Web10.Common.Folder.FolderView, {
				modelHelper: ioc.get('ModelHelper')
			}),
			fileViewFactory: new RuntimeFactory(Web10.Common.File.FileView, {
				modelHelper: ioc.get('ModelHelper'),
				selectedModel: ioc.get('SelectedModel')
			})
		});
	});
	scope.set('FileManager', function(ioc) {
		return new Web10.Dialog.FileManager.FileManagerView({
			fileBrowseTabView: ioc.get('FileBrowseTabView'),
			uploadTabView: ioc.get('UploadTabView')
		});
	});
}
*/

/*
Web10.ContainerReg.Main.prototype.setupFileManager_SelectSingleImage = function() {
	this.ioc.set('FileManager_SelectSingleImage', function(ioc) {
		var selectedModel = new Web10.Domain.SelectedFileSingle();
		
		var browseTabView = ioc.get('FileBrowseTabView');
		browseTab.setModeSelectSingleImage(selectedModel);
		
		var selectedTabView = ioc.get('SelectedTabView');
		selectedTab.setModeSelectSingleImage(selectedModel);
		
		var fm = new Web10.Dialog.FileManager.FileManagerView({
			fileBrowseTabView: browseTabView,
			uploadTabView: ioc.get('UploadTabView')
		});
		fm.setModeShowSelected(selectedTabView);
		
		return fm;
	});
}
*/

Web10.ContainerReg.Main.prototype.setupFileManager = function() {
	this.ioc.set('CurrentFolderModel', this.ioc.singleton(function(ioc) {
		return new Web10.Dialog.FileManager.CurrentFolderModel();
	}));
	this.ioc.set('UploadQueueCol', this.ioc.singleton(function(ioc) {
		return new Web10.Tabs.Upload.UploadQueue();
	}));
	this.ioc.set('UploadQueueView', function(ioc) {
		return new Web10.Tabs.Upload.UploadQueueView({
			collection: ioc.get('UploadQueueCol')
		});
	});
	this.ioc.set('SelectedFilesTabView', function(ioc) {
		return new Web10.Tabs.SelectedFiles.SelectedFilesTabView({
			selectedModel: ioc.get('SelectedModel'),
			modelHelper: ioc.get('ModelHelper')
		});
	});
	this.ioc.set('UploadTabView', function(ioc) {
		return new Web10.Tabs.Upload.UploadTabView({
			currentFolderModel: ioc.get('CurrentFolderModel'),
			fileCol: ioc.get('fileCol'),
			uploadQueueCol: ioc.get('UploadQueueCol'),
			uploadQueueView: ioc.get('UploadQueueView'),
			uploadingItemViewFactory: new RuntimeFactory(Web10.Tabs.Upload.UploadingItemModel)
		});
	});
	this.ioc.set('FolderCrumbsView', function(ioc) {
		return new Web10.Common.FolderCrumbs.FolderCrumbsView({
			currentFolderModel: ioc.get('CurrentFolderModel')
		});
	});
	this.ioc.set('FolderViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Common.Folder.FolderView, {
			modelHelper: ioc.get('ModelHelper')
		});
	});
	this.ioc.set('FileViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Common.File.FileView, {
			modelHelper: ioc.get('ModelHelper'),
			selectedModel: ioc.get('SelectedModel')
		});
	});
	this.ioc.set('FileBrowseTabView', function(ioc) {
		return new Web10.Tabs.FileBrowse.FileBrowseTabView({
			//addFolderPopdownView: ioc.get('AddFolderPopdownView'),
			modelHelper: ioc.get('ModelHelper'),
			folderCrumbsView: ioc.get('FolderCrumbsView'),
			currentFolderModel: ioc.get('CurrentFolderModel'),
			folderCol: ioc.get('folderCol'),
			fileCol: ioc.get('fileCol'),
			folderViewFactory: ioc.get('FolderViewFactory'),
			fileViewFactory: ioc.get('FileViewFactory')
			//folderViewFactory: new RuntimeFactory(Web10.Common.Folder.FolderView, {
			//	modelHelper: ioc.get('ModelHelper')
			//}),
			//fileViewFactory: new RuntimeFactory(Web10.Common.File.FileView, {
			//	modelHelper: ioc.get('ModelHelper')
			//})
		});
	});
	this.ioc.set('FileManager', function(ioc) {
		return new Web10.Dialog.FileManager.FileManagerView({
			fileBrowseTabView: ioc.get('FileBrowseTabView'),
			uploadTabView: ioc.get('UploadTabView')
		});
	});
};

Web10.ContainerReg.Main.prototype.setupPageManager = function(container) {	
	this.ioc.set('PageViewTable', function(ioc) {
		return new Web10.Tabs.PageList.PageViewTable(ioc.get('pageCol'));
	});
	this.ioc.set('PageEditViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Tabs.PageList.PageEditView, {
			modelHelper: ioc.get('ModelHelper'),
			layouts: ioc.get('layouts')
		});
	});
	this.ioc.set('PageListTabView', function(ioc) {
		var view = new Web10.Tabs.PageList.PageListTabView({
			pageCol: ioc.get('pageCol'),
			//pageViewTable: ioc.get('PageViewTable'),
			pageViewCls: Web10.Tabs.PageList.PageView,
			//pageEditViewCls: Web10.Tabs.PageList.PageEditView,
			pageEditViewFactory: ioc.get('PageEditViewFactory'),
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
