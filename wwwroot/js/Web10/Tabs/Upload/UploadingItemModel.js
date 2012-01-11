Namespace('Web10.Tabs.Upload');

Web10.Tabs.Upload.UploadingItemModel = Backbone.Model.extend({
	
	defaults: {
		name: '',
		folderId: 0,
		percent: 0,
		status: null,
		message: ''
	}
	
});
