Namespace('Web10.Domain');

Web10.Domain.FileList = Backbone.Collection.extend({
	
	url: "/api/domain/File",
	
	parse: function(response) {
  	var self = this;
    _.each(response, function(file) {
    	switch (file.type) {
    		case "File":
    			self.add(new Web10.Domain.File(file));
    			break;
    		case "Image":
    			self.add(new Web10.Domain.Image(file));
    			break;
    	}
    });
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
