Namespace('Web10.Tabs.PageList');

// triggers: CloseDialog

Web10.Tabs.PageList.PageListTabView = Web10.Tabs.BaseTabView.extend({//Backbone.View.extend({
	
	className: 'tab PageListTabView',
	
	template: "<button>Save Pages</button><ol class='sortable'></ol>",
	
	initialize: function(opts) {
		Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
		this.pageCol = opts.pageCol;
		this.pageViewCls = opts.pageViewCls;
		//this.pageEditViewCls = opts.pageEditViewCls;
		this.pageEditViewFactory = opts.pageEditViewFactory;
		this.modelHelper = opts.modelHelper;
		//this.pageViewTable = opts.pageViewTable;
		_.bindAll(this, 'render', 'renderOne', 'treeify', 'htmlify', 'insertR', 'saveAllPages');
		this.pageCol.bind('add', this.render, this);
		this.pageCol.bind('reset', this.render, this);
		this.pageCol.bind('destroy', this.render, this);
		this.delegateEvents({
			'click button': 'saveAllPages'
		});
		this.title = 'Your Pages';
	},
	
	saveAllPages: function() {
		var pagesById = {};
		_.each(this.pageCol.models, function(p) { pagesById[p.id] = p; });
		var pages = this.ns.nestedSortable('toArray');
		var ordering = 10;
		_.each(pages, function (page) {
			var id = page.item_id;
			if (id == 'root') return;
			var parentPageId = page.parent_id;
			if (parentPageId == 'root') parentPageId = 0;
			pagesById[id].set({parentPageId:parentPageId, ordering:ordering+=10});
		});
		
		this.pageCol.updateAll();
		this.trigger('CloseDialog');
	},
	
	render: function() {
		$(this.el).html(this.template);
		this.treeify();
		this.htmlify($('ol.sortable', this.el), this.pageTreeRoot);
		this.ns = $('ol.sortable', this.el).nestedSortable({
	    handle: 'div',
	    listType: 'ol',
	    forcePlaceholderSize: true,
	    placeholder: 'placeholder',
	    opacity: 0.6,
	    items: 'li',
	    tolerance: 'pointer',
	    toleranceElement: '> div',
	    helper: 'clone'
	  });
		return this;
	},
	
	htmlify: function($ol, node) {
		if (node.id == 0) {
			var context = this;
			_.each(node.children, function(child) {
				context.htmlify($ol, child);	
			});			
		} else {
			//var view = new Web10.Tabs.PageList.PageView({model:node.model});
			//var editView = new this.pageEditViewCls({model:node.model, modelHelper:this.modelHelper});
			var editView = this.pageEditViewFactory.get({model: node.model});
			var view = new this.pageViewCls({model:node.model, pageEditView:editView});
			//var view = this.pageViewTable.getAt(node.model.get('id'));
			var $li = $("<li></li>");
			$li.attr('id', 'pageId_'+node.model.get('id'));
			$li.html(view.render().el);
			$ol.append($li);
			if (node.children.length > 0) {
				$innerol = $("<ol></ol>");
				for (i in node.children) {
					var child = node.children[i];
					this.htmlify($innerol, child);
				}
				$li.append($innerol);
			}
		}
	},
	
	treeify: function() {
		this.pageTreeRoot = {id:0, children:[], model:null};
		var ptable = {};
		_.each(this.pageCol.models, function(pageModel) {
			var pid = pageModel.get('parentPageId');
			if (ptable[pid] == null)
				ptable[pid] = [];
			ptable[pid].push(pageModel);
		});
		this.insertR(this.pageTreeRoot, ptable, 0);
		return this.pageTreeRoot;
	},
	
	insertR: function(node, ptable, currentPid) {
		if (ptable[currentPid] != null) {
			for (i in ptable[currentPid]) {
				var model = ptable[currentPid][i];
				node.children.push({id:model.get('id'), children:[], model:model});
			}
		}
		for (i in node.children) {
			var child = node.children[i];
			this.insertR(child, ptable, child.id);
		}
	},
	
	renderOne: function(pageModel) {
		//var view = new Web10.Tabs.PageList.PageView({model:pageModel});
		var view = new this.pageViewCls({model:pageModel});
		this.$("ol.sortable").append(view.render().el);
		return this;
	}

});
