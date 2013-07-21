/**
 * CVR ElasticSearch view. Should subclass a more general view,
 * to make it possible to change the content of the result dropdown.
 */
define(['backbone', 'bootstrap', 'translator!'], function(Backbone, undefined, T){
    //console.log(T.test);
    return views.ElasticSearch = Backbone.View.extend({

        defaults: {
            map: {},
            minLength: 3,
        },

        url: function(search){
            return '/api/elasticsearch/' + this.options.index + '/' + search;
        },

        initialize: function() {
            this.options = _.extend(this.defaults, this.options);
            this.options.input = this.$('input[data-provide="typeahead"]');
            this.options.input.typeahead({
                source: _.bind(this.type, this),
                updater: _.bind(this.updateView, this),
                minLength: this.options.minLength
            });
        },

        updateView: function(item){
            var data = this.options.map[item];
            this.fillInputs(data);
            return data.navn;
        },

        fillInputs: function(data){
            this.$('.generated').remove();
            var wrap = $('<div class="generated" />');
            _.each(data, function(value, key, map){
                if(!_.contains([this.options.input.attr('id')], value)){
                    wrap.append($('<label />').attr('for', key).text(key));
                    wrap.append($('<input type="text" />').attr('name', key).attr('id', key).val(value));
                    this.$el.append(wrap);
                }
            }, this);
        },

        type: function(query, process){
            query = $.trim(query);
            if(query.length < this.options.minLength){
                return [];
            }
            $.get(this.url(query), _.bind(function(data){
                this.options.map = _.object(_.map(data, function(value){
                    return [value.navn + " <small>CVR: " + value.cvrnr + "</small>", value];
                }));
                process(_.keys(this.options.map));
            }, this));
        },

        render: function() {
            return this.$el();
        }
    })
});