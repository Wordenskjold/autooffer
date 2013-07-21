define(['backbone'], function(Backbone, undefined, T){
    return views.LandingPage = Backbone.View.extend({

        events: {
          "click button": "validate",
        },

        initialize: function(){
            Backbone.Validation.bind(this);
        },

        validate: function() {
            var email = this.$('input[name="email"]').val();
            var make_model = this.$('input[name="make_model"]').val();
            this.model.set('email', email);
            this.model.set('make_model', make_model);
            if(this.model.isValid(true)){
                this.$('form').submit();
            }
            else{
                this.error();
            }
        },
        error: function(){
            console.log('some error');
        }
    })
});