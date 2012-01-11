Namespace('Web10.Common.SmallView');

Web10.Common.SmallView.SmallView = Backbone.View.extend({

  className: 'SmallView',

  events: {
    'click a.close': 'toggleHide'
  },

  toggleHide: function(e) {
  	if (! this.adjusted) {
  		this.adjustPosition();
  		this.adjusted = true;
  	}
    $(this.el).toggleClass('hide');
  },
  
  template: "<div class='arrowUp'></div><div class='inner'><a href='javascript:void(0);' class='close'>Close</a></div>",
  
  initialize: function(opts) {
  	this.adjusted = false;
    this.innerView = opts.innerView;
    this.parent = opts.parent;
    //this.parent = opts.parent;
  },

  render: function() {
  	$(this.el).html(this.template);
    this.$('.inner').append(this.innerView.innerRender().el);
    return this;
  },

  //must be called after placing the view into the dom
  adjustPosition: function() {
    //var parentOffset = $(this.el).parent().offset();
  	var pw = $(this.parent).width();
  	var parentOffset = $(this.parent).offset();
    var poy = parentOffset.top;
    var pox = parentOffset.left;
    var w = $(this.el).width();
    var ox = pox + (pw/2) - (w/2);
    var oy = poy + 40;
    //ensure it fits
    if (ox < 0) ox = 0;
    //ensure it fits; the '20' below corresponds with the padding:10px for div.SmallView
    if ((ox + w) > $(window).width()) ox = $(window).width() - w - 20; 
    $(this.el).offset({left:ox, top:oy});

    //next lines are for the arrow
    w = $("div.arrowUp", this.el).width();
    var h = $("div.arrowUp", this.el).height();
    //7 corresponds with div.arrowUp size/2; 15 corresponds with div.arrowUp size
    $("div.arrowUp", this.el).offset({left:pox+(pw/2)-7, top:oy-15});
  }

});
