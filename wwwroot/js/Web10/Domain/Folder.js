Namespace('Web10.Domain');

Web10.Domain.Folder = Backbone.Model.extend({
	
	urlRoot: '/api/domain/Folder',
	
	/*
	url : function() {
    if (this.isNew()) 
    	return this.urlRoot;
    else
    	return this.urlRoot + '/' + this.id;
  },
  */
	
	defaults: {
		id: null,
		name: 'Folder Name',
		websiteId: null,
		parentFolderId: null,
		hasSubfolders: false,
		hasFiles: false
	}
	
});
