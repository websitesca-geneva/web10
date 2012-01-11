Namespace('Web10.Tabs.FileBrowse');

Web10.Tabs.FileBrowse.AddFolderPopdownView = Web10.Common.Popdown.PopdownView.extend({
	
	className: "PopdownView AddFolderPopdownView",
	
	template: "<p>Folder name:<br><input type='text' name='name'></p><p><button>Add Folder</button></p>",
	
	events: {
		'click button': 'addFolder'
	},
	
	initialize: function(opts) {
		Web10.Common.Popdown.PopdownView.prototype.initialize.call(this);
		this.title = '+Folder';
		this.folderCol = opts.folderCol;
		this.currentFolderModel = opts.currentFolderModel;
	},

  innerHtml: function() {
		var html = Mustache.to_html(this.template);
		return html;
	},
	
	addFolder: function(e) {
		var name = this.$('input').val();
		var pid = this.currentFolderModel.get('id');
		var folderModel = new Web10.Domain.Folder;
		var view = this;
		folderModel.save({name:name, parentFolderId:pid}, {success: function() {
			view.folderCol.add(folderModel);
		}});
	}

});
