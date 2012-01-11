Namespace('Web10.Common.Folder');

Web10.Common.Folder.FolderView = Backbone.View.extend({
  
	events: {
	  'click div.ContextDialog a[name=delete]': 'remove',
	  'click div.ContextDialog button': 'rename',
		'click a[name=folder]': 'navigate'
	},
	
	className: "FolderView",
	
	template:
		"<div name='outer'>" +
		  "<div class='ContextDialog'>" + 
		    "Rename:<br><input type='text' name='name' value='{{name}}'><button>Save</button>" + 
		    "<ul class='contextMenu'>" +
		      "<li><a href='javascript:void(0);' name='delete'>Delete</a></li>" +
		    "</ul>" +
	    "</div>" +
		"<div name='inner'>" +
			"<a href='javascript:void(0);' name='folder'>{{name}}</a>" +
		"</div>" + 
		"</div>",
	
	initialize: function(opts) {
		_.bindAll(this, 'render', 'remove', 'navigate', 'rename');
		this.currentFolderModel = opts.currentFolderModel;
		this.folderModel = opts.folderModel;
		this.modelHelper = opts.modelHelper;
		this.folderModel.bind('change', this.render, this);
	},
	
	rename: function(e) {
		var name = $(e.target).closest('div.ContextDialog').find('input[name=name]').val();
		this.modelHelper.saveModel({
			model: this.folderModel,
			data: {name: name}
		});
	},
	
	remove: function(e) {
		if (! confirm('Are you sure?')) return;
		this.modelHelper.destroyModel({
			model: this.folderModel
		});
	},
	
	navigate: function(e) {
		this.currentFolderModel.setFolder(this.folderModel);
	},

  render: function() {
		var html = Mustache.to_html(this.template, this.folderModel.toJSON());
		$(this.el).html(html);
		$(".ContextDialog", this.el).contextDialog({
			button: $(this.el)
		});
		return this;
	}

});
