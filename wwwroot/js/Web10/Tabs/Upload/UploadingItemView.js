Namespace('Web10.Tabs.Upload');

Web10.Tabs.Upload.UploadingItemView = Backbone.View.extend({
	
	className: "uploadingItem",
	
	template: "{{name}} ({{size}} bytes): {{percent}}% uploaded. {{message}}",
	
	initialize: function() {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render, this);
	},

  render: function() {
		var html = Mustache.to_html(this.template, this.model.toJSON());
		$(this.el).html(html);
		
		if (this.model.get('status') == 'ok') {
			$(this.el).addClass('complete');
		}
		else if (this.model.get('status') == 'error') {
			$(this.el).addClass('error');
		}
		
		return this;
	}

});
