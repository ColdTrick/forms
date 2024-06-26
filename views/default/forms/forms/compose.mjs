import 'jquery';
import 'jquery-ui';
import i18n from 'elgg/i18n';
import system_messages from 'elgg/system_messages';


function addPage() {
	$(this).parents('li').eq(0).before($('#forms-compose-page-template .forms-compose-list-page').clone());
	
	initSortableSections();
}

function addSection(page) {
	$(this).parents('li').eq(0).before($('#forms-compose-page-template .forms-compose-list-section').clone());
	
	initSortableFields();
}

function addConditionalSection() {
	var $field = $(this).parents('.forms-compose-list-field').eq(0);
	$field.append($('#forms-compose-conditional-section').clone());
	$field.find('.forms-compose-conditional-section').removeAttr('id').show();
	
	initSortableFields();
}

function deleteFormElement(elem) {
	// resolves to parent li (which could be ConditionalSection, Field, Section or Page)
	$(this).parents('.forms-compose-conditional-section, .forms-compose-list-field, .forms-compose-list-section, .forms-compose-list-page').eq(0).remove();
}

function editField(elem) {
	var $field = $(this).parents('.forms-compose-list-field').eq(0);

	var $form = $field.find('.forms-compose-edit-field');
	// close form if it is visible/expanded
	if ($form.is(':visible')) {
		$form.slideToggle(function() { $(this).remove(); });
		return;
	}
	
	// append edit form
	if ($form.length === 0) {
		$field.append($('#forms-compose-edit-field').clone());
		
		// load selector
		$form = $field.find('.forms-compose-edit-field');
		$form.removeAttr('id');
	}
	
	// load form data
	$.each($field.data('params'), function(key, value) {
		$form.find('[name="' + key + '"]').each(function() {
			var $field = $(this);
			
			switch ($field.prop('type')) {
				case 'hidden':
					return;
				case 'checkbox':
				case 'radio':
					$field.prop('checked', value == $field.val());
					break;
				default:
					$field.val(value);
			}
		});
	});
	
	// let drop down tell us which conditional fields to show
	$form.find('[name="#type"]').change();
	
	// show form
	$form.slideToggle();
}

function toggleConditionalFields() {
	var type = $(this).val();
	var $form = $(this).parents('.forms-compose-edit-field').eq(0);
	
	$form.find('.hidden').hide();
	$form.find('.forms-field-for-' + type).show();
}

function saveField(elem) {
	var $field = $(this).parents('.forms-compose-list-field').eq(0);
	
	var $form = $field.find('.forms-compose-edit-field');
	
	var params = $field.data('params');
	
	$.each($form.find('[name]').serializeArray(), function(key, field) {
		params[field.name] = field.value;
	});
	$field.data('params', params);

	$field.find('span:first').html($field.data('params')['#label']);
	// add required indicator
	$field.removeClass('forms-compose-list-field-required');
	if ($field.data('params')['required'] == 1) {
		$field.addClass('forms-compose-list-field-required');
	}
	
	// hide form
	$form.slideToggle(function() { $(this).remove(); });
}

function editTitle(elem) {
	var $title = $(this).prev();
	
	var result = prompt(i18n.echo('forms:compose:edit:title'), $title.text());
	if (result === null) {
		return;
	}
	
	$title.text(result);
}

function toggleElement() {
	$(this).parent().parent().find(' > .ui-sortable').slideToggle();
	$(this).toggleClass('elgg-icon-minus-square-regular fa-minus-square elgg-icon-plus-square-regular fa-plus-square');
}

function initSortablePages() {
	$('.forms-compose-list').sortable({
		axis: 'y',
		items: '.forms-compose-list-page'
	});
	
	initSortableSections();
}

function initSortableSections() {
	$('.forms-compose-list-page > ul').sortable({
		axis: 'y',
		items: '.forms-compose-list-section',
		connectWith: '.forms-compose-list-page > ul'
	});
	
	initSortableFields();
}

function initSortableFields() {
	$('.forms-compose-conditional-section > ul, .forms-compose-list-section > ul').sortable({
		items: '> *:not(.forms-field-unsortable)',
		connectWith: '.forms-compose-conditional-section > ul, .forms-compose-list-section > ul',
		receive: function (event, ui) {
			// remove style added during drag
			$(ui.helper).removeAttr('style');
			
			// if name is missing, probably because it is a new field, add a unique name
			var $data = $(ui.helper).data();
			if (typeof $data.params.name == 'undefined') {
				$data.params.name = '__field_' + new Date().getTime();
				$(ui.helper).data('params', $data.params);
			}
		},
		stop: function(event, ui) {
			if ($(ui.item).parents('.forms-compose-conditional-section').length === 0) {
				// item is not dropped in a conditional section
				return;
			}
			
			if ($(ui.item).find('.forms-compose-conditional-section').length === 0) {
				// dragged item doesn't contain a conditional section, so action is allowed
				return;
			}
			
			// revert sorting action
			$(this).sortable('cancel');
			
			// notify user
			system_messages.error(i18n.echo('forms:compose:conditional_section:invalid_drop'));
		}
	});
}

function saveDefinition() {
	var result = {
		'pages': []
	};
	
	$('.forms-compose-list .forms-compose-list-page').each(function(page_index, page_element) {
		var page = {
			'title': $(page_element).find(' > .forms-compose-title-container .forms-compose-title').eq(0).text(),
			'sections': []
		};
		
		$(this).find('> ul > .forms-compose-list-section').each(function(section_index, section_element) {
			var section = {
				'title': $(section_element).find(' > .forms-compose-title-container .forms-compose-title').eq(0).text(),
				'fields': []
			};
			
			$(this).find('> ul > .forms-compose-list-field').each(function(field_index, field_element) {
				var field = $(field_element).data('params');
				field['conditional_sections'] = [];
				
				$(this).find('> .forms-compose-conditional-section').each(function(conditional_section_index, conditional_section_element) {
					var conditional_value = $(conditional_section_element).find('[name="conditional_value"]').val();
					var conditional_section = {
						'value': conditional_value,
						'fields': []
					};
					
					$(this).find('> ul > .forms-compose-list-field').each(function(conditional_field_index, conditional_field_element) {
						var conditional_field = $(conditional_field_element).data('params');
						conditional_section['fields'].push(conditional_field);
					});
					
					field['conditional_sections'].push(conditional_section);
				});
				
				section['fields'].push(field);
			});
			
			page['sections'].push(section);
		});
		
		result['pages'].push(page);
	});
	
	result = JSON.stringify(result);

	$('.elgg-form-forms-compose input[name="definition"]').val(result);
	$('.elgg-form-forms-compose').submit();
}

initSortablePages();

// draggable
$('.forms-compose-fields > li').draggable({
	helper: 'clone',
	stack: '.forms-compose-list-field',
	connectToSortable: '.forms-compose-list-section > ul, .forms-compose-conditional-section > ul'
});

$(document).on('click', '.forms-compose-add-page', addPage);
$(document).on('click', '.forms-compose-add-section', addSection);
$(document).on('click', '.forms-compose-add-conditional-section', addConditionalSection);
$(document).on('click', '.forms-compose-save', saveDefinition);
$(document).on('click', '.forms-compose-delete', deleteFormElement);
$(document).on('click', '.forms-compose-field-edit', editField);
$(document).on('click', '.forms-compose-field-save', saveField);
$(document).on('change', '.forms-compose-edit-field [name="#type"]', toggleConditionalFields);
$(document).on('click', '.forms-compose-edit-title', editTitle);
$(document).on('click', '.forms-compose-toggle-element', toggleElement);
