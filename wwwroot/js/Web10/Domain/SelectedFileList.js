Namespace('Web10.Domain');

Web10.Domain.SelectedFileList = Backbone.Collection.extend({
	
	model: Web10.Domain.File,
	
	select: function(f) {
    //don't add if already selected because backbone will throw an error
    if (this.isModelSelected(f)) return;
		this.add(f);
	},
	
	getSelected: function() {
		return this.models;
	},
	
	hasSelections: function() {
		return (this.models.length > 0);
	},
	
	isModelSelected: function(fileModel) {
	  var selected = false;
	  this.each(function(file) {
	    if (fileModel.get('id') == file.get('id')) {
	      selected = true;
	    }
	  });
	  return selected;
	},
	
  getModelById: function(id) {
    var ret = null;
    this.each(function(fileModel) {
      if (fileModel.get('id') == id) {
        ret = fileModel;
      }
    });
    return ret;
  }

});
