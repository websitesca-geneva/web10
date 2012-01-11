Namespace('Web10.Domain.Block');

Web10.Domain.Block.ImageGrid = Backbone.Model.extend( {

	url : function() {
		var root = '/api/block/ImageGrid';
		if (this.isNew())
			return root;
		else
			return root + '/' + this.id;
	},

	defaults : {
		id : null,
		images : null
	},

	bindings : function(sysdata, callback) {
		this.bind('saved', callback);
	}

});
