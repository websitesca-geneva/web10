Namespace('Web10.Domain');

Web10.Domain.Page = Backbone.Model.extend({
	
	url : function() {
    var root = '/api/domain/Page';
    if (this.isNew()) 
    	return root;
    else
    	return root + '/' + this.id;
  },
	
	defaults: {
		name: 'New Page',
		title: 'New Page Title',
		layout: null,
		parentPageId: null,
		url: null,
		ordering: 0
	}
	
});
