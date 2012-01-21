Namespace('Web10.Block.Container');

Web10.Block.Container.ContainerDialogView = Backbone.View.extend({
  
  className: 'SimpleDialog ContainerDialogView',
  
  template: 
    "<div class='title'>Add a New Item</div>" +
  	"<div>Template stuff here</div>",
  
  initialize: function(opts) {
    Web10.Dialog.SimpleDialog.prototype.initialize.call(this, opts);
    _.bindAll(this, 'render', 'open');
    this.blockModel = opts.blockModel;
    this.className += ' block' + this.blockModel.get('id');
  },
  
  open: function() {
    $.fancybox($(this.el));
  },
  
  render: function() {
    var html = Mustache.to_html(this.template, this.blockModel);
    $(this.el).html(html);
    return this;
  }

});