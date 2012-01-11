Namespace('Web10.ContainerReg');

Web10.ContainerReg.Block = function(ioc) {
	this.ioc = ioc;
};

Web10.ContainerReg.Block.prototype.register = function() {
	this.setupImageGridDialogView();
	this.setupImageDialogView();
	this.setupMenuDialogView();
	this.setupTextDialogView();
};

Web10.ContainerReg.Block.prototype.setupImageGridDialogView = function() {
	var scope = this.ioc.child('ImageGridDialogViewFactory');
	
	scope.set('SelectedModel', scope.singleton(function(ioc) {
		return new Web10.Domain.SelectedFileList();
	}));
	
	scope.set('FileViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Common.File.FileView, {
			modelHelper: scope.get('ModelHelper'),
			selectedModel: scope.get('SelectedModel')
		});
	});
	
	scope.set('SelectedFileViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Tabs.SelectedFiles.SelectedFileView, {
			modelHelper: scope.get('ModelHelper'),
			selectedModel: scope.get('SelectedModel')
		});
	});
	
	scope.set('SelectedFilesTabView', function(ioc) {
		return new Web10.Tabs.SelectedFiles.SelectedFilesTabView({
			selectedModel: scope.get('SelectedModel'),
			modelHelper: scope.get('ModelHelper'),
			selectedFileViewFactory: scope.get('SelectedFileViewFactory')
		});
	});
	
	scope.set('ImageGridDialogViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Block.ImageGrid.ImageGridDialogView, {
			fileBrowseTabView: scope.get('FileBrowseTabView'),
			uploadTabView: scope.get('UploadTabView'),
			selectedFilesTabView: scope.get('SelectedFilesTabView'),
			blockModel: scope.get('model'),
			selectedFileListModel: scope.get('SelectedModel'),
			modelHelper: scope.get('ModelHelper'),
			fileCol: scope.get('fileCol')
		});
	});
}

//maybe this way
Web10.ContainerReg.Block.prototype.setupImageDialogView = function() {
	var scope = this.ioc.child('ImageDialogViewFactory');
	
	scope.set('SelectedModel', scope.singleton(function(ioc) {
		return new Web10.Domain.SelectedFileSingle();
	}));
	
	scope.set('FileViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Common.File.FileView, {
			modelHelper: ioc.get('ModelHelper'),
			selectedModel: ioc.get('SelectedModel')
		});
	});
	
	scope.set('SelectedFilesTabView', function(ioc) {
		return new Web10.Tabs.SelectedFiles.SelectedFilesTabView({
			selectedModel: ioc.get('SelectedModel'),
			modelHelper: ioc.get('ModelHelper')
		});
	});
	
	scope.set('ImageDialogViewFactory', function(ioc) {
		//var scope = ioc.raw('ImageDialogView');
		return new RuntimeFactory(Web10.Block.Image.ImageDialogView, {
			fileBrowseTabView: scope.get('FileBrowseTabView'),
			uploadTabView: scope.get('UploadTabView'),
			selectedFilesTabView: scope.get('SelectedFilesTabView'),
			blockModel: ioc.get('model'),
			selectedFileSingleModel: ioc.get('SelectedModel'),
			modelHelper: ioc.get('ModelHelper')
		});
	});
}

/*
//maybe this way
Web10.ContainerReg.Block.prototype.setupImageDialogView = function() {
	
	var scope = this.ioc.child('SelectSingleImageScope');
	
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
	
	
	this.ioc.set('ImageDialogView', function(ioc) {
		return new Web10.Block.Image.ImageDialogView({
			fileBrowseTabView: scope.get('FileBrowseTabView'),
			uploadTabView: scope.get('UploadTabView')
		});
		
	}
}
*/

Web10.ContainerReg.Block.prototype.setupMenuDialogView = function() {
	this.ioc.set('MenuDialogViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Block.Menu.MenuDialogView, {
			pageListTabView: ioc.get('PageListTabView')
		});
	});
};

Web10.ContainerReg.Block.prototype.setupTextDialogView = function() {
	this.ioc.set('TextTabViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Block.Text.TextTabView, {
			modelHelper: ioc.get('ModelHelper')
		});
	});
	this.ioc.set('TextDialogViewFactory', function(ioc) {
		return new RuntimeFactory(Web10.Block.Text.TextDialogView, {
			textTabViewFactory: ioc.get('TextTabViewFactory')
		});
	});
};
