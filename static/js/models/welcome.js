define(['backbone'], function(Backbone){
	return models.Welcome = Backbone.Model.extend({

		initialize: function(){
			this.set('test', 1);
		}
	});
});