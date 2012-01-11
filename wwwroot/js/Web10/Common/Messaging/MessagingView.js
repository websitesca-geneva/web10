Namespace('Web10.Common.Messaging');

Web10.Common.Messaging.MessagingView = Backbone.View.extend({
	
	className: 'MessagingView',
	
	events: {
		'click': 'clickIt'
	},
	
	initialize: function() {
		//_.bindAll(this, 'render', 'click');
		this.model.bind('show', this.render, this);
		this.sticky = true;
	},
	
	clickIt: function(e) {
		if (this.sticky)
			$(this.el).clearQueue();
		else
			$(this.el).fadeOut();
		this.sticky = !this.sticky;
	},
	
	render: function() {
		$(this.el).html(this.model.get('message'));
		$(this.el).fadeIn().delay(2000).fadeOut();
		if (this.model.get('isError'))
			$(this.el).attr('class', 'MessagingView error');
		else
			$(this.el).attr('class', 'MessagingView success');
		this.model.set({isError:false}, {silent:true});
		return this;
	}
	
});
