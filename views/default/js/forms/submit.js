define(function(require) {
	
	var $ = require('jquery');
	var elgg = require('elgg');
	
	var checkConditional = function() {
		var name = $(this).attr('name');
		var value = $(this).val();
		
		$('[data-conditional-field="' + name + '"]').hide();
		$('[data-conditional-field="' + name + '"][data-conditional-value="' + value + '"]').show();
	};
	
	// sortable
	var init = function() {
		
		$(document).on('change', '.forms-submit-conditional', checkConditional);

	};
	
	elgg.register_hook_handler('init', 'system', init);
	
	
});
