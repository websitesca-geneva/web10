Namespace('Web10.Block.Text');

Web10.Block.Text.TextDialogView = Web10.Dialog.TabbedDialog.extend({
	
	className: 'TabbedDialog TextDialogView',
	
	initialize: function(opts) {
		Web10.Dialog.TabbedDialog.prototype.initialize.call(this);
	
		this.textTabViewFactory = opts.textTabViewFactory;
		this.blockModel = opts.blockModel;
		
		var self = this;
		
		//tab1
		//var v = new Web10.Block.Text.TextTabView({model:this.model, dialog:this});
		this.textTabView = this.textTabViewFactory.get({
			blockModel: this.blockModel
		});
		this.addTab(this.textTabView);
		this.textTabView.bind('CloseDialog', function() {
			self.close();
		});
		
		this.title = 'Text Block';
	}

});
