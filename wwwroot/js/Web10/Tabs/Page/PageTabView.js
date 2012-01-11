Namespace('Web10.Tabs.Page');

// triggers: SelectTab(tabIndex)

Web10.Tabs.Page.PageTabView = Web10.Tabs.BaseTabView.extend({

	events: {
		"click button": "addButtonClick",
		"keyup input[name=name]": "updateUrl"
	},
	
	className: 'tab PageTabView',
	
	template: 
	"<form>" +
	"<table>" + 
	"<tr><td>Page Name:</td><td><input type='text' name='name'></td></tr>" +
	"<tr><td>Layout:</td><td><select name='layout'>{{#layouts}}<option value='{{.}}'>{{.}}</option>{{/layouts}}</p></select></td></tr>" +
	"<tr><td>Url:</td><td>http://hostname/<span name='url'></span></td></tr>" +
	"<tr><td colspan='2'><button>Add</button></td></tr>" +
	"</table>" +
	"</form>",
	
	initialize: function(opts) {
		Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
		this.layouts = opts.layouts;
		this.pageCol = opts.pageCol;
		this.modelHelper = opts.modelHelper;
		//this.dialog = opts.dialog;
		_.bindAll(this, 'render', 'addButtonClick', 'updateUrl');
		this.title = 'Add Page';
	},
	
	render: function() {
		var html = Mustache.to_html(this.template, {layouts:this.layouts});
		$(this.el).html(html);
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
	
	addButtonClick: function(e) {
		var data = $(e.target).closest('form').serializeObject();
		data.url = '/' + $(e.target).closest('form').find('span[name=url]').html();
		var p = new Web10.Domain.Page();
		var view = this;
		this.modelHelper.saveModel({
			model: p,
			data: data,
			success: function() {
				view.pageCol.add(p);
				view.trigger('SelectTab', {tabIndex:0});
			}
		});
		/*
		p.save(data, {success: function(model, resp, xhr) {
			view.pageCol.add(model);
			//view.dialog.selectTabRel(0);
			view.trigger('SelectTab', {tabIndex:0});
		}});
		*/
		
		return false;
	}

});
