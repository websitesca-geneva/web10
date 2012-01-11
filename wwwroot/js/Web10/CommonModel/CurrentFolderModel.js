Namespace('Web10.CommonModel');

Web10.CommonModel.CurrentFolderModel = Backbone.Model.extend({
	
	defaults: {
		id: null,
		crumbs: []
	},
	
	setFolderAsRoot: function() {
		this.set({id:null, crumbs:[]});
	},
	
	setFolder: function(folderModel) {
		var id = folderModel.get('id');
		var crumbs = this.get('crumbs');
		var ids = _.map(crumbs, function(fm) { return fm.get('id') });
		var i = _.indexOf(ids, id);
		if (i < 0) 
			crumbs.push(folderModel);
		else 
			crumbs = _.initial(crumbs, crumbs.length-i-1);
		this.set({id:id, crumbs:crumbs});
	}
	
});
