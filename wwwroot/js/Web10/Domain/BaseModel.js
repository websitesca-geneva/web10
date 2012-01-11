Namespace('Web10.Domain');

Web10.Domain.BaseModel = Backbone.Model.extend({

	/*
	saveMacro: function(data, successCallback, errorCallback) {
		var messagingModel = Web10.mainContainer.get('MessagingModel'); //todo: pass this in instead of global access to maincontainer
		var callbacks = {
			success: function(model, resp, xhr) {
				//Web10.Editing.messaging.setMessage('Success!');
				//model.trigger('messaging', {msg:'Success!'});
				messagingModel.setMessage('Success!');
				model.trigger('saved');
				if (successCallback) successCallback.call();
			},
			error: function(model, resp, xhr) {
				//model.trigger('messaging', {msg:resp.responseText, isError:true});
				//Web10.Editing.messaging.setMessage('Error: ' + resp.responseText, true);
				messagingModel.setMessage('Error: '+resp.responseText, true);
				if (errorCallback) errorCallback.call();
			}
		};
		this.save(data, callbacks);
	}
	*/
	
});
