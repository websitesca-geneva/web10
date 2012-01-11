Namespace('Web10.Common.File');

Web10.Common.File.FileView = Backbone.View.extend({
	
	className: "FileView",
	
	fileTemplate: "{{name}}",
	
	imageTemplate:
		"<div name='outer'>" +
			"<div class='ContextDialog'>" + 
				"Rename:<br><input type='text' name='name' value='{{name}}'><button>Save</button>" + 
				"<ul class='contextMenu'>" +
				  "<li><a href='javascript:void(0);' name='delete'>Delete</a></li>" +
				"</ul>" +
			"</div>" +
		"<div name='inner'>" +
			"{{name}}<br>" +
			"<img src='/data/account_{{accountId}}/website_{{websiteId}}/image_{{id}}_100.{{ext}}'>" +
		"</div>" + 
		"</div>",
	
	events: {
		'click div.ContextDialog ul.contextMenu a[name=delete]': 'remove',
		'click div.ContextDialog ul.contextMenu a[name=select]': 'select',
		'click div.ContextDialog button': 'rename'
	},
	
	initialize: function(opts) {
		_.bindAll(this, 'render', 'remove', 'rename', 'select');
		this.fileModel = opts.fileModel;
		this.modelHelper = opts.modelHelper;
		this.selectedModel = opts.selectedModel;
		this.fileModel.bind('change', this.render, this);
		if (this.selectedModel) {
		  this.selectedModel.bind('remove', this.render, this);
		  this.selectedModel.bind('change', this.render, this);
		  this.selectedModel.bind('add', this.render, this);
		}
	},
	
	select: function(e) {
		this.$(".ContextDialog").addClass('hide');
		this.selectedModel.select(this.fileModel);
	},
	
	rename: function(e) {
		var name = $(e.target).closest('div.ContextDialog').find('input[name=name]').val();
		this.modelHelper.saveModel({
			model: this.fileModel,
			data: {name: name}
		});
	},
	
	remove: function(e) {
		this.modelHelper.destroyModel({
			model:this.fileModel
		});
	},

  render: function() {
		if (this.fileModel.get('type') == 'File') {
			var html = Mustache.to_html(this.fileTemplate, this.fileModel.toJSON());
		}
		else if (this.fileModel.get('type') == 'Image') {
			var html = Mustache.to_html(this.imageTemplate, this.fileModel.toJSON());
			this.className = 'FileView image';
		}
		$(this.el).html(html);
		$(this.el).attr('cid', this.fileModel.cid);
		$(this.el).attr('fileid', this.fileModel.get('id'));
		$(".ContextDialog", this.el).contextDialog({
			button: $(this.el)
		});
		if (this.selectedModel) {
  		if (this.selectedModel != null) {
  			this.$('ul.contextMenu').append("<li><a href='javascript:void(0);' name='select'>Select</a></li>")
  		}
  		if (this.selectedModel.isModelSelected(this.fileModel)) {
  		  $(this.el).addClass('selected');
  		} else {
  		  $(this.el).removeClass('selected');
  		}
		}
		return this;
	}

});
