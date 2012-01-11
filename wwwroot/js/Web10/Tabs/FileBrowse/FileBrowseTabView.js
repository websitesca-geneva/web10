Namespace('Web10.Tabs.FileBrowse');

Web10.Tabs.FileBrowse.FileBrowseTabView = Web10.Tabs.BaseTabView.extend({
	
	className: 'tab FileBrowseTabView',
	
	events: {
		'click a[name=root]': 'navigateRoot',
		'click div.addFolder div.ContextDialog button': 'newFolder'
	},
	
	newFolder: function(e) {
		var name = $(e.target).closest('.ContextDialog').find('input[name=name]').val();
		var pid = this.currentFolderModel.get('id');
		var folder = new Web10.Domain.Folder();
		var $contextDialog = this.$('.addFolder .ContextDialog');
		var self = this;
		var data = {
			name: name,
			parentFolderId: pid
		};
		this.modelHelper.saveModel({
			model:folder,
			data: data,
			success: function(model, resp, xhr) {
				$contextDialog.toggleClass('hide');
				self.folderCol.add(model);
			}
		});
		return false;
	},
	
	template:
		"<div>" +
		  "<div class='addFolder'>"+
		  "<a href='javascript:void(0);' class='addFolderDialogButton'>+Folder</a>" +
		  "<div class='ContextDialog'>Folder name:<br><input type='text' name='name'><button>Add Folder</button></div>" +
		  "</div> | " +
		  "Current Folder: <a href='javascript:void(0);' name='root' class='crumb'>Root</a> / <span class='breadcrumbs'></span>"+
		"</div>" +
		"<div class='clear'></div>" +
		"<ol></ol><div class='clear'></div>",
	
	
	initialize: function(opts) {
		Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
		_.bindAll(this, 'render', 'renderOneFolder', 'renderOneFile', 'newFolder');
		
		//this.addFolderPopdownView = opts.addFolderPopdownView;
		this.modelHelper = opts.modelHelper;
		this.folderCrumbsView = opts.folderCrumbsView;
		this.currentFolderModel = opts.currentFolderModel;
		this.folderCol = opts.folderCol;
		this.fileCol = opts.fileCol;
		//this.folderViewTable = opts.folderViewTable;
		//this.fileViewTable = opts.fileViewTable;
		this.folderViewFactory = opts.folderViewFactory;
		this.fileViewFactory = opts.fileViewFactory;
		
		this.folderCol.bind('reset', this.render, this);
		this.folderCol.bind('add', this.render, this);
		this.folderCol.bind('destroy', this.render, this);
		
		this.fileCol.bind('reset', this.render, this);
		this.fileCol.bind('add', this.renderOneFile, this);
		this.fileCol.bind('destroy', this.render, this);
		
		this.currentFolderModel.bind('change', this.render, this);
		
		this.title = 'Browse';
	},
	
	render: function() {
		$(this.el).html(this.template);
		
		var view = this;
		
		var folders = _.filter(this.folderCol.models, function(m) { 
			return (m.get('parentFolderId') == view.currentFolderModel.get('id'));
		});
		var files = _.filter(this.fileCol.models, function(m) { 
			return (m.get('folderId') == view.currentFolderModel.get('id'));
		});
		
		_.each(folders, function(folderModel) { view.renderOneFolder(folderModel); });
		_.each(files, function(fileModel) { view.renderOneFile(fileModel); });
		
		//this.$("span.addFolderPopdown").html(this.addFolderPopdownView.render().el);
		this.$("div.addFolder .ContextDialog").contextDialog({
			button: this.$('.addFolder a.addFolderDialogButton')
		});
		this.$("span.breadcrumbs").html(this.folderCrumbsView.render().el);
		
		return this;
	},
	
	navigateRoot: function(e) {
		this.currentFolderModel.setFolderAsRoot();
	},
	
	renderOneFolder: function(folderModel) {
		var self = this;
		var view = this.folderViewFactory.get({
			folderModel: folderModel,
			currentFolderModel: self.currentFolderModel
		});
		//var view = this.folderViewTable.getAt(folderModel.get('id'));
		$li = $("<li></li>");
		$li.append(view.render().el);
		this.$("ol").append($li);
		return this;
	},

	renderOneFile: function(fileModel) {
		var self = this;
		var view = this.fileViewFactory.get({
			fileModel: fileModel
		});
		//var view = this.fileViewTable.getAt(fileModel.get('id'));
		$li = $("<li></li>");
		$li.append(view.render().el);
		this.$("ol").append($li);
		return this;
	}

});
