Namespace('Web10.Block.ImageGrid');

Web10.Block.ImageGrid.ImageGridDialogView = Web10.Dialog.TabbedDialog.extend({
	
	className: 'TabbedDialog ImageGridDialogView',
	
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
		this.selectedFileListModel = opts.selectedFileListModel;
		this.fileCol = opts.fileCol;
		
		this.addTab(this.fileBrowseTabView);
		this.addTab(this.selectedFilesTabView);
		this.addTab(this.uploadTabView);
		
		this.setFooter('<button>Save Selected</button>');
		
		//initialize by selecting the images from blockModel
		var images = this.blockModel.get('images');
		images.sort(function(a,b) { return a.ordering-b.ordering; });
		if (images.length > 0) {
		  for (i in images) {
		    var img = images[i];
		    var fileModel = this.fileCol.getModelById(img.imageId);
		    this.selectedFileListModel.select(fileModel);
		  }
		}
		
		this.title = 'ImageGrid Block';
	},
	
	saveSelected: function(e) {
	  //need to get the ordering from the html, that is the only place the ordering is stored
	  var self = this;
	  var selectedFiles = [];
	  this.$('.SelectedFilesTabView ol.fileList li>div').each(function(i, el) {
	    var fileId = $(el).attr('fileid');
	    selectedFiles.push(self.selectedFileListModel.getModelById(fileId));
	  });
	  
	  var newdata = this.blockModel.clone();
	  newdata.set({images:selectedFiles});
		this.modelHelper.saveModel({
			model: this.blockModel,
			data: newdata
		});
		this.close();
	}
	
});
