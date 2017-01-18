define(function(require) {
	
	var $ = require('jquery');
	var elgg = require('elgg');
	
	var checkConditional = function() {
		var name = $(this).attr('name');
		var value = $(this).val();
		
		$('[data-conditional-field="' + name + '"]').hide();
		$('[data-conditional-field="' + name + '"][data-conditional-value="' + value + '"]').show();
	};
	
	var checkValidity = function(event) {
		// unset custom error message
		this.setCustomValidity('');
		
		if (this.validity.patternMismatch) {
			// invalid so set custom error
			this.setCustomValidity($(this).data('customErrorMessage'));
		}
	};
	
	// sortable
	var init = function() {
		
		$(document).on('change', '.forms-submit-conditional', checkConditional);
		$(document).on('input', '.elgg-form-forms-submit [data-custom-error-message]', checkValidity);
	};
	
	elgg.register_hook_handler('init', 'system', init);
});
