Namespace('Web10.Domain.Block');

Web10.Domain.Block.ImageGrid_Image = Backbone.Model.extend( {

  url : function() {
    var root = '/api/block/ImageGrid_Image';
    if (this.isNew())
      return root;
    else
      return root + '/' + this.id;
  },

  defaults : {
    id : null,
    imageId: null,
    ordering: 0,
    fileModel: null
  },

  bindings : function(sysdata, callback) {
    this.bind('saved', callback);
  }

});
