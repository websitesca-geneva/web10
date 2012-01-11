Namespace('Web10.Dialog.FileManager');

Web10.Dialog.FileManager.FileManagerView = Web10.Dialog.TabbedDialog.extend({
	
	className: 'TabbedDialog FileManagerView',
	
	initialize: function(opts) {
		Web10.Dialog.TabbedDialog.prototype.initialize.call(this, this.model);
		this.fileBrowseTabView = opts.fileBrowseTabView;
		this.uploadTabView = opts.uploadTabView;
		
		this.addTab(this.fileBrowseTabView);
		this.addTab(this.uploadTabView);
		
		this.title = 'File and Image Manager';
	},
	
	//render happens in TabbedDialog (parent class)
	
});
