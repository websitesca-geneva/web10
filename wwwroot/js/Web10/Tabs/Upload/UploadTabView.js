Namespace('Web10.Tabs.Upload');

Web10.Tabs.Upload.UploadTabView = Web10.Tabs.BaseTabView.extend({
	
	className: 'tab UploadTabView',
	
	template:
		"<div class='dropzone'>" +
		"Drop files here to upload." +
		"<br>Current Folder: Root / <span class='folderPath'></span>" +
		"</div>",
	
	initialize: function(opts) {
		Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
		_.bindAll(this, 'render', 'up_enter', 'up_leave', 'up_start', 'up_progress', 'up_finish', 'up_complete');
		//this.uploadQueue = new Web10.Tabs.Upload.UploadQueue;
		this.currentFolderModel = opts.currentFolderModel;
		this.uploadQueueCol = opts.uploadQueueCol;
		this.uploadQueueView = opts.uploadQueueView;
		this.uploadingItemModelFactory = opts.uploadingItemModelFactory;
		this.currentFolderModel.bind('change', this.render, this);
		this.fileCol = opts.fileCol;
		this.title = 'Upload Files';
		
		//this.queueView = new Web10.Tabs.Upload.UploadQueueView({collection:this.uploadQueue});
	},
	
	render: function() {
		$(this.el).html(this.template);
		
		$('div.dropzone', this.el).dropup({
		  url: '/upload.php',
		  params: { parentFolderId: this.currentFolderModel.get('id') },
		  max_file_size: 8,
		  on_enter: this.up_enter,
		  on_leave: this.up_leave,
		  on_start: this.up_start,
		  on_progress: this.up_progress,
		  on_finish: this.up_finish,
		  on_complete: this.up_complete
		});
		
		var names = _.map(this.currentFolderModel.get('crumbs'), function(folderModel) {
			return folderModel.get('name');
		});
		this.$(".folderPath").append(names.join(" / "));
		
		$(this.el).append(this.uploadQueueView.render().el);
		
		return this;
	},
	
	up_enter: function() {
		$('.dropzone', this.el).addClass('hovering');
	},
	
	up_leave: function() {
		$('.dropzone', this.el).removeClass('hovering');
	},
	
	up_start: function($index, $file) {
		//first remove a file with the same name from queue, if any
		_.each(this.uploadQueueCol.models, function(uploadingItemModel) {
			if (uploadingItemModel.get('name') == $file.name) {
				uploadingItemModel.destroy();
			}
		});
		$('.dropzone', this.el).removeClass('hovering');
		var newItem = new Web10.Tabs.Upload.UploadingItemModel;
		newItem.set({index:$index, name:$file.name, size:$file.size});
		this.uploadQueueCol.add(newItem);
	},
	
	up_progress: function($index, $file, $per) {
		_.each(this.uploadQueueCol.models, function(uploadingItemModel) {
			if (uploadingItemModel.get('name') == $file.name) {
				uploadingItemModel.set({percent:$per});
			}
		});
	},
	
	up_finish: function($index, $file, $json) {
		var self = this;
		_.each(this.uploadQueueCol.models, function(uploadingItemModel) {
			if (uploadingItemModel.get('name') == $file.name) {
				if ($json.status == 'ERROR') {
					uploadingItemModel.set({status:'error', message:$json.message});
				}
				else {
					uploadingItemModel.set({status:'ok'});
					//raise event with $json.file
					//self.trigger('NewFile', {file:$json.file});
					self.fileCol.add($json.file);
				}
				return;
			}
		});
	},
	
	up_complete: function() {
	}

});
