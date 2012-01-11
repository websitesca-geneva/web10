Namespace('Web10.Domain.Block');

Web10.Domain.Block.Image = Backbone.Model.extend({
	
	url : function() {
    var root = '/api/block/Image';
    if (this.isNew()) 
    	return root;
    else
    	return root + '/' + this.id;
  },
	
	defaults: {
		id: null,
		imageId: null,
		defaultSrc: null
	},
  
  bindings: function(sysdata, callback) {
		this.bind('saved', callback);
  }
	
});
