define(function(require) {
	
	var $ = require('jquery');
	var elgg = require('elgg');
	
	var checkConditional = function() {
		var name = $(this).attr('name');
		var value = $(this).val();
		
		$('[data-conditional-field="' + name + '"]').hide();
		$('[data-conditional-field="' + name + '"][data-conditional-value="' + value + '"]').show();
	};
	
	var clearCustomErrorMessage = function () {
		this.setCustomValidity('');
	};
	
	var setCustomErrorMessage = function(event) {
		
		// invalid based on regex?
		if (this.validity.patternMismatch) {
			// yes, set custom error
			this.setCustomValidity($(this).data('customErrorMessage'));
		}
	};
	
	var tabNavClick = function(event) {
		
		if ($(this).parent().hasClass('elgg-state-selected')) {
			// clicking on current selected tab
			return;
		}
		
		// validate form
		var $form = $(this).closest('.elgg-form-forms-submit');
		var $active_section = $form.find('.elgg-tabs-content > .elgg-state-active');
		
		var $inputs = $active_section.find('input, select, textarea');
		var valid = true;
		$inputs.each(function(index, elem) {
			
			if (!elem.willValidate) {
				// this element will not be validated (eg button)
				return;
			}
			
			if ($(elem).is(':hidden')) {
				// input is not shown (eg conditional section)
				return;
			}
			
			if (!elem.checkValidity()) {
				if (valid) {
					// select first invalid element
					$(elem).focus();
				}
				
				if (!elem.validity.customError) {
					elem.setCustomValidity(elem.validationMessage);
				}
				valid = false;
			}
		});
		
		if (!valid) {
			event.preventDefault();
			event.stopPropagation();
			event.stopImmediatePropagation();
			
			return false;
		}
	};
	
	var navButtonClick = function() {
		
		var $active_tab = $(this).closest('.elgg-form-forms-submit').find('.elgg-tabs > .elgg-state-selected').eq(0);
		
		if ($(this).hasClass('forms-submit-buttons-prev')) {
			// prev
			$active_tab.prev().find('a').click();
		} else {
			// next
			$active_tab.next().find('a').click();
		}
	};
	
	var preventEnter = function(event) {
		
		if (event.which !== 13) {
			return;
		}
		
		if (!$('.elgg-form-forms-submit .elgg-module-tabs').length) {
			// no tabs on this form
			return;
		}
		
		// stop event
		event.preventDefault();
		
		// find 'submit' button (next or submit) in current section
		$('.elgg-form-forms-submit .elgg-tabs-content > .elgg-state-active .forms-submit-buttons .elgg-button-submit').click();
		
		return false;
	};
	
	// sortable
	var init = function() {
		
		$(document).on('change', '.forms-submit-conditional', checkConditional);
		
		$(document).on('input', '.elgg-form-forms-submit input, .elgg-form-forms-submit textarea', clearCustomErrorMessage);
		$(document).on('change', '.elgg-form-forms-submit select', clearCustomErrorMessage);
		$(document).on('input', '.elgg-form-forms-submit [data-custom-error-message]', setCustomErrorMessage);
		
		$(document).on('click', '.elgg-form-forms-submit .forms-submit-buttons-prev, .elgg-form-forms-submit .forms-submit-buttons-next', navButtonClick);
		$(document).on('click', '.elgg-form-forms-submit .elgg-tabs a', tabNavClick);
		$(document).on('keydown', '.elgg-form-forms-submit', preventEnter);
	};
	
	elgg.register_hook_handler('init', 'system', init);
});
