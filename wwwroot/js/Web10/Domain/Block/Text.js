Namespace('Web10.Domain.Block');

Web10.Domain.Block.Text = Web10.Domain.BaseModel.extend({//Backbone.Model.extend({
	
	url : function() {
    var root = '/api/block/Text';
    if (this.isNew()) 
    	return root;
    else
    	return root + '/' + this.id;
  },
	
	defaults: {
		id: null,
		text: 'Text goes here.'
	},
  
  bindings: function(sysdata, callback) {
		this.bind('saved', callback);
  }
	
});
