import 'jquery';

$(document).on('change', '#forms-edit-endpoint-selector', function () {
	var $endpoints = $('form.elgg-form-forms-edit .forms-edit-endpoint');
	$endpoints.addClass('hidden');
	
	$endpoints.find('[required]').prop('disabled', true);
	
	var $endpoint = $endpoints.filter('.forms-edit-endpoint-' + $(this).val());
	$endpoint.find('[required]').prop('disabled', false);
	$endpoint.removeClass('hidden');
});

$('#forms-edit-endpoint-selector').trigger('change');
