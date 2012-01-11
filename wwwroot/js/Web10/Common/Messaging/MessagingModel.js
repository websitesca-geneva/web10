Namespace('Web10.Common.Messaging');

Web10.Common.Messaging.MessagingModel = Backbone.Model.extend({
	
	defaults: {
		message: 'Message goes here',
		isError: false
	},

	setMessage: function(msg, isError) {
		isError = (isError) ? true : false;
		this.set({message:msg, isError:isError}, {silent:true});
		this.trigger('show');
		this.set({isError:false}, {silent:true});
	}
	
});
