define(function (require) {
	var $ = require('jquery');
	
	var switch_endpoints = function () {
		var $endpoints = $('form.elgg-form-forms-edit .forms-edit-endpoint');
		$endpoints.addClass('hidden');
		
		$endpoints.find('[required]').prop('disabled', true);
		
		var $endpoint = $endpoints.filter('.forms-edit-endpoint-' + $(this).val());
		$endpoint.find('[required]').prop('disabled', false);
		$endpoint.removeClass('hidden');
	};
	
	$(document).on('change', '#forms-edit-endpoint-selector', switch_endpoints);
	$('#forms-edit-endpoint-selector').trigger('change');
});
