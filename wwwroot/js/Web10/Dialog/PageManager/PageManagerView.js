Namespace('Web10.Dialog.PageManager');

Web10.Dialog.PageManager.PageManagerView = Web10.Dialog.TabbedDialog.extend({
	
	className: 'TabbedDialog PageManagerView',
	
	initialize: function(opts) {
		Web10.Dialog.TabbedDialog.prototype.initialize.call(this, {title:'Page Manager'});
		this.pageCol = opts.pageCol;
		this.layouts = opts.layouts;
		this.pageListTabView = opts.pageListTabView;
		this.pageTabView = opts.pageTabView;
		
		var self = this;
		
		//tab1
		//var tabView = new Web10.Tabs.PageList.PageListTabView({pageCol:this.pageCol});
		this.addTab(this.pageListTabView);
		this.pageListTabView.bind('CloseDialog', function() {
			self.close();
		});
		
		//tab2
		//tabView = new Web10.Tabs.Page.PageTabView({pageCol:this.pageCol, layouts:this.layouts});
		this.addTab(this.pageTabView);
		this.pageTabView.bind('SelectTab', function(params) {
			self.selectTabRel(params.tabIndex);
		});
		
		this.title = 'Page Manager';
	}
	
});
