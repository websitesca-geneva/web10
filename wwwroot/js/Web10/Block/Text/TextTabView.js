Namespace('Web10.Block.Text');

// triggers: CloseDialog

Web10.Block.Text.TextTabView = Web10.Tabs.BaseTabView.extend({

	events: {
		"click button": "saveText"
	},
	
	className: 'tab PageTabView',
	
	template: 
	"Text:" +
	"<p><textarea rows='20' cols='40' name='text'>{{text}}</textarea>" + 
	"<p><button>Save Text</button>",
	
	initialize: function(opts) {
		Web10.Tabs.BaseTabView.prototype.initialize.call(this, opts);
		_.bindAll(this, 'render', 'saveText');
		//this.sysdata = opts.sysdata;
		this.blockModel = opts.blockModel;
		this.modelHelper = opts.modelHelper;
		this.title = 'Edit Text';
	},
	
	render: function() {
		var html = Mustache.to_html(this.template, this.blockModel.toJSON());
		$(this.el).html(html);
		return this;
	},
	
	saveText: function(e) {
		var text = $(e.target).closest('.tab').find('textarea[name=text]').val();
		//this.model.saveMacro({text:text});
		this.modelHelper.saveModel({
			model: this.blockModel,
			data: {text:text}
		})
		//this.dialog.close();
		this.trigger('CloseDialog');
		return false;
	}

});
