Namespace('Web10.Tabs');

Web10.Tabs.BaseTabView = Backbone.View.extend({

	initialize: function(args) {
		this.tabid = Web10.Tabs.BaseTabView.tabCount++;
		this.title = 'Tab Title';
		$(this.el).attr('tabid', this.tabid);
	}

});

//static tabCount
Web10.Tabs.BaseTabView.tabCount = 0;
