Namespace('Web10.Block.Container');

Web10.Block.Container.BlockChooserTabView = Web10.Tabs.BaseTabView.extend({
  
  className: 'tab BlockChooserTabView',
  
  template: "template here",
  
  initialize: function(opts) {
    Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
    _.bindAll(this, 'render');
    this.title = 'Choose a Block Type';
    
    
  },
  
  render: function() {
    $(this.el).html(this.template);
    
    
    
    return this;
  }
  
});
