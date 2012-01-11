Namespace('Web10.Tabs.SelectedFiles');

Web10.Tabs.SelectedFiles.SelectedFileView = Backbone.View.extend({
	
	className: "SelectedFileView",
	
	fileTemplate: "{{name}}",
	
	imageTemplate: 
	  "<div>" +
	  "<a href='javascript:void(0);' class='remove'><img src='/img/x.png' border='0' alt='remove'></a>" +
	  "<img src='/data/account_{{accountId}}/website_{{websiteId}}/image_{{id}}_100.{{ext}}'>" +
	  "</div>",
	
	events: {
		'click a.remove': 'remove',
		'mouseenter': 'enter',
		'mouseleave': 'leave'
	},
	
	enter: function() {
	  $('a.remove', this.el).addClass('show');
	},
	
	leave: function() {
	  $('a.remove', this.el).removeClass('show');
	},
	
	initialize: function(opts) {
		_.bindAll(this, 'render', 'remove', 'enter', 'leave');
		this.fileModel = opts.fileModel;
		this.modelHelper = opts.modelHelper;
		this.selectedModel = opts.selectedModel;
		this.fileModel.bind('change', this.render, this);
	},
	
	remove: function(e) {
		this.selectedModel.remove(this.fileModel);
	},

	render: function() {
		if (this.fileModel.get('type') == 'File') {
			var html = Mustache.to_html(this.fileTemplate, this.fileModel.toJSON());
		}
		else if (this.fileModel.get('type') == 'Image') {
			var html = Mustache.to_html(this.imageTemplate, this.fileModel.toJSON());
			this.className = 'SelectedFileView image';
		}
		$(this.el).html(html);
		$(this.el).attr('cid', this.fileModel.cid);
		$(this.el).attr('fileid', this.fileModel.get('id'));
		$(".ContextDialog", this.el).contextDialog({
			button: $(this.el)
		});
		return this;
	}

});
