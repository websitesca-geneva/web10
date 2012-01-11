Namespace('Web10.Block.Menu');

Web10.Block.Menu.MenuDialogView = Web10.Dialog.TabbedDialog.extend( {

  className : 'TabbedDialog MenuDialogView',

  initialize : function(opts) {
    Web10.Dialog.TabbedDialog.prototype.initialize.call(this, this.model);

    this.pageListTabView = opts.pageListTabView;

    var self = this;

    this.addTab(this.pageListTabView);
    this.pageListTabView.bind('CloseDialog', function() {
      self.close();
    });

    this.title = 'Menu Block';
  }

});
