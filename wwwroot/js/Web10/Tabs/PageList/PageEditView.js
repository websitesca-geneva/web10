Namespace('Web10.Tabs.PageList');

Web10.Tabs.PageList.PageEditView = Backbone.View.extend({

	events: {
		"click button[name=save]": "saveButtonClick",
		"click button[name=delete]": "deleteButtonClick",
		"keyup input[name=name]": "updateUrl"
	},
	
	className: 'PageEditView',
	
	template: 
	"<form>" +
	"<table>" + 
	"<tr><td>Name:</td><td><input type='text' name='name' value='{{name}}'> &nbsp; http://hostname/<span name='url'></span></td></tr>" +
	"<tr><td>Title:</td><td><input type='text' name='title' value='{{title}}' size='60'></td></tr>" +
	"<tr><td>Layout:</td><td><select name='layout'>{{#layouts}}<option value='{{name}}' {{selected}}>{{name}}</option>{{/layouts}}</select></td></tr>" +
	"<tr><td colspan='2'><button name='save'>Save Page</button> <button name='delete'>Delete Page</button></td></tr>" +
	"</table>" +
	"</form>",
	
	initialize: function(opts) {
		this.modelHelper = opts.modelHelper;
		this.layouts = opts.layouts;
		_.bindAll(this, 'render', 'saveButtonClick', 'deleteButtonClick', 'updateUrl');
	},
	
	render: function() {
		var data = this.model.toJSON();
		var layouts = _.map(this.layouts, function(l) {
			return {name: l, selected: (l==data.layout?'selected':'')};
		});
		data.layouts = layouts;
		var html = Mustache.to_html(this.template, data);
		$(this.el).html(html);
		this.updateUrl();
		this.delegateEvents(this.events);
		return this;
	},
	
	updateUrl: function() {
		var url = this.$("input[name=name]").val().trim();
		var re = /[^a-z0-9]+/gi;
		url = url.replace(/[.']+/g, '');
		url = url.replace(/[^a-z0-9]+/gi, '-');
		url = url.toLowerCase();
		this.$('span[name=url]').html(url);
	},
	
	deleteButtonClick: function(e) {
		//this.model.destroy();
		this.modelHelper.destroyModel({model:this.model});
		return false;
	},
	
	saveButtonClick: function(e) {
		var data = $(e.target).closest('form').serializeObject();
		data.url = '/' + $(e.target).closest('form').find('span[name=url]').html();
		
		this.modelHelper.saveModel({
			model: this.model, 
			data: data
		});
		
		return false;
	}

});
