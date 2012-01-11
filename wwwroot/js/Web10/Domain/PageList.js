Namespace('Web10.Domain');

Web10.Domain.PageList = Backbone.Collection.extend({
	
	model: Web10.Domain.Page,
	
	url: '/api/domain/PageList',
	
	comparator: function(page) {
		return page.get('ordering');
	},
	
	updateAll: function() {
		var collection = this;
		var messagingModel = Web10.ioc.get('MessagingModel');
		options = {
			success: function(model, resp, xhr) {
				collection.reset(model);
				//Web10.Editing.messaging.setMessage('Success!');//
				//collection.trigger('messaging', {msg:'Success!'});
				messagingModel.setMessage('Success!');
				collection.trigger('saved');//
			},
			error: function(model, resp, xhr) {//
				//collection.trigger('messaging', {msg:resp.responseText, isError:true});
				//Web10.Editing.messaging.setMessage('Error: ' + resp.responseText, true);//
				messagingModel.setMessage('Error: ' + resp.responseText, true);
			}//
		};
		
  	return Backbone.sync('update', this, options);
	}

});
