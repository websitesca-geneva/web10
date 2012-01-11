Namespace('Web10.Common');

Web10.Common.ModelHelper = function(opts) {
	this.messagingModel = opts.messagingModel;
};

Web10.Common.ModelHelper.prototype.destroyModel = function(opts) {
	var model = opts.model;
	var success = opts.success;
	var error = opts.error;
	var self = this;
	var callbacks = {
		success: function(model, resp, xhr) {
			self.messagingModel.setMessage('Deleted OK!');
			if (success) success(model, resp, xhr);
		},
		error: function(model, resp, xhr) {
			self.messagingModel.setMessage('Error: ' + resp.responseText, true);
			if (error) error(model, resp, xhr);
		}
	};
	model.destroy(callbacks);
};

Web10.Common.ModelHelper.prototype.saveModel = function(opts) {
	var model = opts.model;
	var data = opts.data;
	var success = opts.success;
	var error = opts.error;
	
	var self = this;
	var callbacks = {
		success: function(model, resp, xhr) {
			//Web10.Editing.messaging.setMessage('Success!');
			//model.trigger('messaging', {msg:'Success!'});
			self.messagingModel.setMessage('Success!');
			model.trigger('saved');
			if (success) success(model);
		},
		error: function(model, resp, xhr) {
			//model.trigger('messaging', {msg:resp.responseText, isError:true});
			//Web10.Editing.messaging.setMessage('Error: ' + resp.responseText, true);
			self.messagingModel.setMessage('Error: ' + resp.responseText, true);
			if (error) error(model);
		}
	};
	model.save(data, callbacks);
};
