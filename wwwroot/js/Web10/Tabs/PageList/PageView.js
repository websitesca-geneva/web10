Namespace('Web10.Tabs.PageList');

Web10.Tabs.PageList.PageView = Backbone.View.extend({
  
	events: {
		'mouseenter': 'enter',
		'mouseleave': 'leave',
		'click a.editButton': 'toggleEditDiv'
	},
	
	className: "PageView",
	
	template: "{{name}} <a href='javascript:void(0);' class='editButton'>Edit</a>" +
		"<div class='edit'></div>",
	
	initialize: function(opts) {
		_.bindAll(this, 'render', 'toggleEditDiv','enter', 'leave');
		this.model.bind('change', this.render, this);
		//this.pageEditView = new Web10.Tabs.PageList.PageEditView({model:this.model});
		this.pageEditView = opts.pageEditView;
	},

  render: function() {
		var newhtml = Mustache.to_html(this.template, this.model.toJSON());
		$(this.el).html(newhtml);
		$("div.edit", this.el).append(this.pageEditView.render().el);
		return this;
	},
	
	toggleEditDiv: function(e) {
		this.$('div.edit').toggleClass('show');
	},
	
	enter: function(e) {
		$(e.target).addClass('show');
	},
	
	leave: function(e) {
		$(e.target).removeClass('show');
	},

});
