Namespace('Web10.Common.FolderCrumbs');

Web10.Common.FolderCrumbs.FolderCrumbsView = Backbone.View.extend({
	
	tagName: 'span',
	
	className: "FolderCrumbsView",
	
	//template: "{{#breadcrumbs}}<a href='javascript:void(0);' class='crumb'>{{name}}</a> / {{/breadcrumbs}}",
	
	initialize: function(opts) {
		_.bindAll(this, 'render');
		this.currentFolderModel = opts.currentFolderModel;
		this.currentFolderModel.bind('change', this.render, this);
	},

  render: function() {
		$(this.el).html('');
		var view = this;
		_.each(this.currentFolderModel.get('crumbs'), function(folderModel) {
			var $link = $("<a href='javascript:void(0);'>"+folderModel.get('name')+"</a><span> / </span>");
			$link.click(function(e) {
				view.currentFolderModel.setFolder(folderModel);
			});
			$(view.el).append($link);
		});
		return this;
	}

});
