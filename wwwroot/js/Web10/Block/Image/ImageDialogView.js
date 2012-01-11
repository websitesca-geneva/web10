Namespace('Web10.Block.Image');

Web10.Block.Image.ImageDialogView = Web10.Dialog.TabbedDialog.extend({
	
	className: 'TabbedDialog ImageDialogView',
	
	events: function() {
		var e = {'click button': 'saveSelected'};
		return _.extend(e, Web10.Dialog.TabbedDialog.prototype.events);
	},
	
	initialize: function(opts) {
		Web10.Dialog.TabbedDialog.prototype.initialize.call(this);
		_.bindAll(this, 'saveSelected');
		
		this.fileBrowseTabView = opts.fileBrowseTabView;
		this.uploadTabView = opts.uploadTabView;
		this.selectedFilesTabView = opts.selectedFilesTabView;
		this.blockModel = opts.blockModel;
		this.modelHelper = opts.modelHelper;
		this.selectedFileSingleModel = opts.selectedFileSingleModel;
		
		this.addTab(this.fileBrowseTabView);
		this.addTab(this.uploadTabView);
		this.addTab(this.selectedFilesTabView);
		
		this.setFooter('<button>Save Selected</button>');
		
		this.title = 'Image Block';
	},
	
	saveSelected: function(e) {
		var selectedFile = this.selectedFileSingleModel.getSelected();
		if (selectedFile == null) {
			alert('here');
			return;
		}
		
		var imageId = this.selectedFileSingleModel.getSelected().get('id');
		this.modelHelper.saveModel({
			model: this.blockModel,
			data: {imageId: imageId}
		});
		
		this.close();
	}
	
});
