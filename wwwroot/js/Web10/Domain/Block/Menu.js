Namespace('Web10.Domain.Block');

Web10.Domain.Block.Menu = Backbone.Model.extend({
	
	url : function() {
    var root = '/api/block/Menu';
    if (this.isNew()) 
    	return root;
    else
    	return root + '/' + this.id;
  },
	
	defaults: {
		id: null,
		pageHierarchyDepth: 1
	},
  
  bindings: function(sysdata, callback) {
		this.bind('saved', callback);
  	sysdata['pageCol'].bind('saved', callback);
  }
	
});
