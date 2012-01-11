Namespace('Web10.Tabs.SelectedFiles');

Web10.Tabs.SelectedFiles.SelectedFilesTabView = Web10.Tabs.BaseTabView.extend({
	
	className: 'tab SelectedFilesTabView',
	
	listTemplate: "<ol class='fileList'></ol>",
		
	singleTemplate: "The image you chose is:<p> {{name}}<p>" +
		"<img src='/data/account_{{accountId}}/website_{{websiteId}}/image_{{id}}_300.{{ext}}'>",
	
	emptyTemplate: "Nothing selected yet.",
	
	initialize: function(opts) {
		Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
		_.bindAll(this, 'render');
		
		this.selectedModel = opts.selectedModel;
		this.selectedModel.bind('change', this.render, this);
		this.selectedModel.bind('add', this.render, this);
		this.selectedModel.bind('remove', this.render, this);
		
		this.selectedFileViewFactory = opts.selectedFileViewFactory;
		
		this.title = 'Re-order';
	},
	
	render: function() {
		if (! this.selectedModel.hasSelections()) {
			$(this.el).html(this.emptyTemplate);
		} else if (this.selectedModel instanceof Backbone.Collection) {
			$(this.el).html(this.listTemplate);
			var self = this;
			this.selectedModel.each(function (fileModel) {
				var view = self.selectedFileViewFactory.get({fileModel:fileModel});
				var $li = $("<li></li>");
				$li.append(view.render().el);
				$(".fileList", self.el).append($li);
			});
			$("ol.fileList", this.el).sortable();
			$("ol.fileList", this.el).disableSelection();
		} else {
			var file = this.selectedModel.getSelected();
			var html = Mustache.to_html(this.singleTemplate, file.toJSON());
			$(this.el).html(html);
		}
		
		
		return this;
	}
	
});
