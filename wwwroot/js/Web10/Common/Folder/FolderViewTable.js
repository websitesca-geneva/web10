Namespace('Web10.Common.Folder');

Web10.Common.Folder.FolderViewTable = function(folderCol, currentFolderModel) {
	this.folderCol = folderCol;
	this.currentFolderModel = currentFolderModel;
	this.table = {};
	this.init();
}

Web10.Common.Folder.FolderViewTable.prototype.init = function() {
	self = this;
	this.folderCol.each(function(folderModel) {
		var view = new Web10.Common.Folder.FolderView({
			folderModel: folderModel,
			currentFolderModel: self.currentFolderModel
		});
		self.table[folderModel.get('id')] = view;
	});
}

Web10.Common.Folder.FolderViewTable.prototype.getAt = function(i) {
	return this.table[i];
}
