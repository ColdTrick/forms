define(function(require) {
	
	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');
	
	var make_friendly_title = function() {
		if ($('#friendly_url').val().length) {
			return;
		}
		
		var ajax = new Ajax(false);
		ajax.view('form/friendly_title', {
			data: {
				'title': $(this).val()
			},
			complete: function(data) {
				if (!data || !data.responseJSON) {
					return;
				}
				
				var result = data.responseJSON;
				if (result.friendly_url) {
					$('#friendly_url').prop('value', result.friendly_url);
				}
			}
		});
	};
	
	$(document).on('change', '#form_title', make_friendly_title);
});
