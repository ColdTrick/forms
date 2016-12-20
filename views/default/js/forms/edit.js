define(function(require) {
	
	var $ = require('jquery');
	var elgg = require('elgg');
	
	var make_friendly_title = function() {
		if ($('#friendly_url').val().length) {
			return;
		}
		
		var value = $(this).val();
		value = value.toLowerCase();
		value = value.replace(/\W/g, '-');
		value = value.replace(/(-)\1{1,}/g, '$1');
		value = value.replace(/^[-]+/, '');
		value = value.replace(/[-]+$/, '');
		
		$('#friendly_url').prop('value', value);
	};
	
	$(document).on('change', '#form_title', make_friendly_title);
});
