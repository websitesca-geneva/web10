Namespace('Web10.Dialog');

Web10.Dialog.TabbedDialog = Backbone.View.extend({
	
	className: 'TabbedDialog',
	
	template: "<div class='dialogTitle'>(<a href='javascript:void(0);' name='close'>Close</a>) </div>" +
		"<div class='dialogBody'>" +
		"<div class='tabs'>" + 
			"<div class='tabsMenu'><ul></ul></div>" +
			"<div class='tabsBody'></div>" +
			"<div class='tabsFooter'></div>" +
		"</div>" +
		"</div>",
		
	menuItemTemplate: "<li tabid={{tabid}}><a href='javascript:void(0);'>{{title}}</a></li>",
		
	events: {
		'click div.tabsMenu a': 'tabMenuClick',
		'click a[name=close]': 'close'
	},
	
	toggleOpen: function() {
		$(this.el).toggleClass('hidden');
	},
	
	close: function() {
		$(this.el).addClass('hidden');
	},
	
	open: function() {
		$(this.el).removeClass('hidden');
	},
	
	initialize: function(args) {
		//dont' need to call Backbone.View.prototype.initialize, it doesn't do anything
		_.bindAll(this, 'render', 'renderOne', 'selectTab', 'tabMenuClick', 'close', 'open');
		this.selectedTabid = null; //nothing selected yet
		this.tabs = [];
		this.tabCount = 0;
		this.footer = null;
	},
	
	setFooter: function(html) {
		this.footer = html;
	},
	
	addTab: function(tab) {
		this.tabs.push(tab);
		tab.dialog = this;
		tab.bind('CloseDialog', this.close, this);
	},
	
	tabMenuClick: function(e) {
		var $target = $(e.target);
		var tabid = $target.closest('li').attr('tabid');
		this.selectTab(tabid);
	},
	
	render: function() {
		$(this.el).html(this.template);
		this.$('div.dialogTitle').append(this.title);
		_.each(this.tabs, this.renderOne);
		if (this.tabs.length > 0)
		{
			var firstTabid = this.tabs[0].tabid;
			this.selectTab(firstTabid);
		}
		if (this.footer) {
			this.$('.tabs .tabsFooter').html(this.footer);
		}
		return this;
	},
	
	selectTabRel: function(index) {
		if (this.tabs[index]) {
			var tabid = this.tabs[index].tabid;
			this.selectTab(tabid);
		}
	},
	
	selectTab: function(tabid) {
		var liSel = 'div.tabsMenu li[tabid='+this.selectedTabid+']';
		var tabSel = 'div.tabsBody div.tab[tabid='+this.selectedTabid+']';
		this.$(liSel).removeClass('selected');
		this.$(tabSel).removeClass('selected');
		
		liSel = 'div.tabsMenu li[tabid='+tabid+']';
		tabSel = 'div.tabsBody div.tab[tabid='+tabid+']';
		this.$(liSel).addClass('selected');
		this.$(tabSel).addClass('selected');
		this.selectedTabid = tabid;
	},
	
	renderOne: function(tabView) {
		var li = Mustache.to_html(this.menuItemTemplate, {tabid:tabView.tabid, title:tabView.title});
		this.$('div.dialogBody div.tabsMenu ul').append(li);
		this.$('div.dialogBody div.tabsBody').append(tabView.render().el);
	}
	
});//class TabbedDialog
