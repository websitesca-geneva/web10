Namespace('Web10.Domain');

Web10.Domain.SelectedFileSingle = Backbone.Model.extend({
	
	defaults: {
		selected: null
	},
	
	select: function(f) {
		this.set({selected:f});
	},
	
	getSelected: function() {
		return this.get('selected');
	},
	
	hasSelections: function() {
		return (this.get('selected') != null);
	},
	
	isModelSelected: function(fileModel) {
	  var selected = this.get('selected');
	  if (selected) {
	    if (selected.get('id') == fileModel.get('id')) {
	      return true;
	    }
	  }
	  return false;
  }
	
});
