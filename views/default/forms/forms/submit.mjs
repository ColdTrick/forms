import 'jquery';
import 'page/components/tabs';

function checkConditional() {
	var name = $(this).attr('name');
	var input_values = $(this).val();
	
	if ($(this).hasClass('elgg-input-radios')) {
		name = $(this).find('.elgg-input-radio').eq(0).attr('name');
		input_values = $(this).find('.elgg-input-radio:checked').val();
	}

	if (name.length < 1) {
		return;
	}
	
	// strip [] from multiselects
	name = name.replace('[]', '');
	
	var $conditionals = $('[data-conditional-field="' + name + '"]');

	// hide all sections
	$conditionals.hide();
	$conditionals.find('input, select, textarea').prop('disabled', true);
	
	// values could be an array in case of a multiselect, so always make it an array
	var values = [].concat(input_values);
	values.forEach(function(value) {
		if (value.length < 1) {
			return;
		}
		
		// show correct section
		$conditionals.filter('[data-conditional-value="' + value + '"]').show();
		$conditionals.filter('[data-conditional-value="' + value + '"]').find('input, select, textarea').prop('disabled', false);
	});
}

function clearCustomErrorMessage() {
	this.setCustomValidity('');
	
	if ($(this).attr('type') === 'radio' || $(this).attr('type') === 'checkbox') {
		var $form = $(this).closest('.elgg-form-forms-submit');
		$form.find('input[type="' + $(this).attr('type') + '"][name="' + $(this).attr('name') + '"]').each(function(index, elem) {
			elem.setCustomValidity('');
		})
	}
}

function tabNavClick(event) {
	var $tab = $(this).parent();
	if ($tab.hasClass('elgg-state-selected')) {
		// clicking on current selected tab
		return;
	}
	
	if ($tab.hasClass('elgg-state-disabled')) {
		// not going to next page
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		return false;
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
		
		if (!elem.reportValidity()) {
			valid = false;
			
			return false;
		}
	});
	
	if (!valid) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		return false;
	}
	
	$tab.next().removeClass('elgg-state-disabled');
	window.scrollTo(0, 0);
}

function navButtonClick() {
	var $active_tab = $(this).closest('.elgg-form-forms-submit').find('.elgg-tabs > .elgg-state-selected').eq(0);
	
	if ($(this).hasClass('forms-submit-buttons-prev')) {
		// prev
		$active_tab.prev().find('a').click();
	} else {
		// next
		$active_tab.next().find('a').click();
	}
}

function preventEnter(event) {
	if (event.which !== 13) {
		return;
	}
	
	if ($(event.target).is('textarea')) {
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
}

function checkRequiredCheckboxes(event) {
	var $checkboxes_container = $(this).parents('.elgg-input-checkboxes');
	var $checkboxes = $checkboxes_container.find('input[type="checkbox"]');
	if (!$checkboxes.filter('[required]').length) {
		return;
	}

	if ($checkboxes.filter(':checked').length) {
		$checkboxes.filter(':not(:checked)').prop('required', false);
		$checkboxes.filter(':checked').prop('required', true);
	} else {
		$checkboxes.prop('required', true);
	}
}

$(document).on('change', '.forms-submit-conditional', checkConditional);

$(document).on('input', '.elgg-form-forms-submit input, .elgg-form-forms-submit textarea', clearCustomErrorMessage);
$(document).on('change', '.elgg-form-forms-submit select, .elgg-form-forms-submit input[type="radio"], .elgg-form-forms-submit input[type="checkbox"]', clearCustomErrorMessage);

$(document).on('change', '.elgg-form-forms-submit .elgg-input-checkboxes input[type="checkbox"]', checkRequiredCheckboxes);

$(document).on('click', '.elgg-form-forms-submit .forms-submit-buttons-prev, .elgg-form-forms-submit .forms-submit-buttons-next', navButtonClick);
$('body').on('click', '.elgg-form-forms-submit .elgg-tabs a', tabNavClick); // register on body to be before tab switch in page/components/tabs.js
$(document).on('keydown', '.elgg-form-forms-submit', preventEnter);
