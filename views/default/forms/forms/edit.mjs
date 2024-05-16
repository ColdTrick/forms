import 'jquery';
import Ajax from 'elgg/Ajax';

$(document).on('change', '#form_title', function() {
	if ($('#friendly_url').val().length) {
		return;
	}
	
	var ajax = new Ajax(false);
	ajax.view('form/friendly_title', {
		data: {
			'title': $(this).val(),
			'view': 'json'
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
});
