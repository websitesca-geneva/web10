Namespace('Web10.Tabs.ImageSelector');

Web10.Tabs.ImageSelector.ImageSelectorTabView = Web10.Tabs.BaseTabView.extend({
	
	className: 'tab ImageSelectorTabView',
	
	events: {
		'click a[name=root]': 'navigateRoot'
	},
	
	template:
		"Current Folder: <a href='javascript:void(0);' name='root' class='crumb'>Root</a> / <span class='breadcrumbs'></span></div>" +
		"<div style='clear:both;'></div>" +
		"<ol></ol><div class='clear'></div>",
	
	initialize: function(opts) {
		Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
		_.bindAll(this, 'render', 'renderOneFolder', 'renderOneFile');
		
		this.currentFolderModel = opts.currentFolderModel;
		this.folderCol = opts.folderCol;
		this.fileCol = opts.fileCol;
		
		o = {currentFolderModel: this.currentFolderModel};
		this.crumbsView = new Web10.Common.FolderCrumbs.FolderCrumbsView(o);
		
		this.folderCol.bind('reset', this.render, this);
		this.folderCol.bind('add', this.renderOneFolder, this);
		this.folderCol.bind('destroy', this.render, this);
		
		this.fileCol.bind('reset', this.render, this);
		this.fileCol.bind('add', this.renderOneFile, this);
		this.fileCol.bind('destroy', this.render, this);
		
		this.currentFolderModel.bind('change', this.render, this);
		
		this.title = 'Select an Image';
	},
	
	render: function() {
		$(this.el).html(this.template);
		
		var view = this;
		
		var folders = _.filter(this.folderCol.models, function(m) { 
			return (m.get('parentFolderId') == view.currentFolderModel.get('id'));
		});
		var files = _.filter(this.fileCol.models, function(m) { 
			return (m.get('folderId') == this.currentFolderModel.get('id'));
		}, this);
		
		_.each(folders, function(folderModel) { view.renderOneFolder(folderModel); });
		_.each(files, function(fileModel) { view.renderOneFile(fileModel); });
		
		this.$("span.breadcrumbs").html(this.crumbsView.render().el);
		
		return this;
	},
	
	navigateRoot: function(e) {
		this.currentFolderModel.setFolderAsRoot();
	},
	
	renderOneFolder: function(folderModel) {
		var opts = {folderModel:folderModel, currentFolderModel:this.currentFolderModel};
		var view = new Web10.Common.Folder.FolderView(opts);
		$li = $("<li></li>");
		$li.append(view.render().el);
		this.$("ol").append($li);
		return this;
	},

	renderOneFile: function(fileModel) {
		var opts = {fileModel:fileModel};
		var view = new Web10.Common.File.FileView(opts);
		$li = $("<li></li>");
		$li.append(view.render().el);
		this.$("ol").append($li);
		return this;
	}

});
