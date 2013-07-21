define(['backbone', 'backbone-validation'], function(Backbone){
	return models.PreLaunchSubscriber = Backbone.Model.extend({

		validation: {
			email: {
				pattern: 'email'
			},
			make_model: {
				required: true
			}
		},

		initialize: function(){
			_.extend(Backbone.Model.prototype, Backbone.Validation.mixin);
		}
	});
});