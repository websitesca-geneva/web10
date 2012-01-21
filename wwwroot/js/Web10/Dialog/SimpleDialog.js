Namespace('Web10.Dialog');

Web10.Dialog.SimpleDialog = Backbone.View.extend({
  
  //className: 'SimpleDialog',
  
  parentTemplate: "<div>" +
  		"<div class='dialogTitle'>Title here</div>" +
    "<div class='dialogBody'>Body here</div>" +
    "</div>",
  
  toggleOpen: function() {
    //$(this.el).toggleClass('hidden');
  },
  
  close: function() {
    //$(this.el).addClass('hidden');
  },
  
  open: function() {
    $.fancybox($('.SimpleDialog.block'+this.blockModel.get('id')));
  },
  
  initialize: function(opts) {
    _.bindAll(this, 'render');
    this.blockModel = opts.blockModel;
    this.className += ' blah';
  },
  
  render: function() {
    var $html = $(this.parentTemplate);
    $html.find('div.dialogTitle').html(this.title);
    $html.find('div.dialogBody').html(this.body);
    $(this.el).html($html);
    //$.fancybox(this.el);
    //$.fancybox('hello here' + this.title + this.body);
    return this;
  }
  
});