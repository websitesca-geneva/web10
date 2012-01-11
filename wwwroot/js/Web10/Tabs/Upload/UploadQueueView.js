Namespace('Web10.Tabs.Upload');

Web10.Tabs.Upload.UploadQueueView = Backbone.View.extend({
	
	className: 'UploadQueueView',
	
	template: "<ol></ol>",
	
	initialize: function(args) {
		_.bindAll(this, 'render');
		this.collection.bind('add', this.render, this);
		this.collection.bind('destroy', this.render, this);
	},
	
	render: function() {
		$(this.el).html(this.template);
		var self = this;
		_.each(this.collection.models, function(uploadingItemModel) {
			var view = new Web10.Tabs.Upload.UploadingItemView({model:uploadingItemModel});
			var $li = $("<li></li>");
			$li.append(view.render().el);
			$("ol", self.el).append($li);
		});
		return this;
	}

});
