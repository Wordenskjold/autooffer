require.config({
    baseUrl: '/static/js',
    paths: {
		'backbone':           'libs/backbone/backbone.min',
		'backbone-validation': 'libs/backbone/backbone-validation-min',
		'backbone-modelbinder': 'libs/backbone/backbone-modelbinder.min',
		'backbone-collectionbinder': 'libs/backbone/backbone-collectionbinder.min',
		'jquery':              'libs/jquery/jquery.min',
		'underscore':          'libs/underscore/underscore.min',
		'json2':               'libs/json2/json2',
		'modernizr':           'libs/modernizr/modernizr.min',
		'bootstrap':           'libs/bootstrap/js/bootstrap.min',
		'translator':          'translator'

    },
	shim: {
		'underscore': {
			exports: '_'
		},
		'jquery': {
			exports: '$'
		},
		'translator': {
			deps: ['jquery'],
			exports: 'T'
		},
		'backbone': {
			deps: ['jquery', 'underscore', 'json2'],
			exports: 'Backbone'
		},
		'backbone-validation': ['backbone'],
		'backbone-modelbinder': ['backbone'],
		'backbone-collectionbinder': ['backbone'],
		'bootstrap': ['jquery']
	},
});

require(['modernizr']);

window.views       = {};
window.models      = {};
window.collections = {};