Namespace('Web10.Domain');

Web10.Domain.File = Backbone.Model.extend({
	
	url: '/api/domain/File',
	
	defaults: {
		id: null,
		name: 'Folder Name',
		websiteId: 0,
		folderId: null,
		ext: null,
		type: null
	}
	
});
