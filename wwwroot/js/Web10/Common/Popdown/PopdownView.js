Namespace('Web10.Common.Popdown');

Web10.Common.Popdown.PopdownView = Backbone.View.extend({
	
	className: 'PopdownView',
	
	outerTemplate: "<a name='button' href='javascript:void(0);'>{{title}}</a>" +
			"<div name='inner'>INNER</div>",
	
	events: {},
			
	parentEvents: {
		'click a': 'toggle'
	},
			

	
	render: function() {
		var html = Mustache.to_html(this.outerTemplate, {title:this.title});
		$(this.el).html(html);
		if (this.innerHtml) {
			this.$('div[name=inner]').html(this.innerHtml());
		}
		_.extend(this.events, this.parentEvents);
		this.delegateEvents();
		return this;
	},
	
	toggle: function(e) {
		this.$('div[name=inner]').toggleClass('active');
		this.$('a[name=button]').toggleClass('active');
	}
	
});
