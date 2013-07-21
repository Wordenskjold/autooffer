define({
	load: function(name, req, onload, config){
		if(!name){
			name = 'api/translator';
		}
		$.get(name, function(data){
			onload(data);
		});
	}
});