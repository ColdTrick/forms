define(function(require) {
	
	var $ = require('jquery');
	

	var addPage = function() {
		
		var html = '<li class="forms-compose-list-page"><span>' + elgg.echo('forms:compose:page:new') + '</span>';
		html += '<ul><li class="forms-compose-list-section"><span>' + elgg.echo('forms:compose:section:new') + '</span>';
		html += '<ul></ul></li></ul></li>';
		
		$(this).parents('li').eq(0).before(html);
		
		initSortableSections();
	};
	
	var addSection = function(page) {
		var html = '<li class="forms-compose-list-section"><span>' + elgg.echo('forms:compose:section:new') + '</span>';
		html += '<ul></ul></li>';
		
		$(this).parents('li').eq(0).before(html);
		
		initSortableFields();
	};
	
	var addConditionalSection = function() {
		var $field = $(this).parents('.forms-compose-list-field').eq(0);
		$field.append($('#forms-compose-conditional-section').clone());
		$field.find('.forms-compose-conditional-section').removeAttr('id').show();
		
		initSortableFields();
	};

	var deleteFormElement = function(elem) {	
		// resolves to parent li (which could be ConditionalSection, Field, Section or Page)
		$(this).parents('.forms-compose-conditional-section, .forms-compose-list-field, .forms-compose-list-section, .forms-compose-list-page').eq(0).remove();
	};
	
	var editField = function(elem) {
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
				if ($field.is('[type="checkbox"]')) {
					$field.prop('checked', value == $field.val());
				} else {
					$field.val(value);
				}
			});
		});
		
		// let drop down tell us which conditional fields to show
		$form.find('[name="#type"]').change();
		
		// show form
		$form.slideToggle();
	};
	
	var toggleConditionalFields = function() {
		
		var type = $(this).val();
		var $form = $(this).parents('.forms-compose-edit-field').eq(0);
		
		$form.find('.hidden').hide();
		$form.find('.forms-field-for-' + type).show();		
	};
	
	var saveField = function(elem) {
		var $field = $(this).parents('.forms-compose-list-field').eq(0);
		
		var $form = $field.find('.forms-compose-edit-field');
		
		var params = $field.data('params');
		
		$.each($form.find('[name]').serializeArray(), function(key, field) {
			params[field.name] = field.value;
		});
		$field.data('params', params);

		$field.find('span:first').html($field.data('params')['#label']);
		
		// hide form
		$form.slideToggle(function() { $(this).remove(); });
	};
	
	var addEditTitleLink = function(elem) {
		if ($(this).next().is('.forms-compose-edit-title')) {
			return;
		}
		
		$(this).after('<span class="forms-compose-edit-title">' + elgg.echo('forms:compose:edit:title') + '</span>');
	};
	
	var editTitle = function(elem) {
		var $title = $(this).prev(); 
		
		var result = prompt(elgg.echo('forms:compose:edit:title'), $title.text());
		if (result === null) {
			return;
		}
		
		$title.text(result);
	};
	
	var initSortablePages = function() {
		$('.forms-compose-list').sortable({
			axis: 'y',
			items: '.forms-compose-list-page'
		});
		
		initSortableSections();
	};

	var initSortableSections = function() {
		$('.forms-compose-list-page > ul').sortable({
			axis: 'y',
			items: '.forms-compose-list-section',
			connectWith: '.forms-compose-list-page > ul'			
		});
		
		initSortableFields();
	};
	
	var initSortableFields = function() {
		$('.forms-compose-conditional-section > ul, .forms-compose-list-section > ul').sortable({
			items: '> *:not(.forms-field-unsortable)',
			connectWith: '.forms-compose-conditional-section > ul, .forms-compose-list-section > ul',
			receive: function (event, ui) {
				// remove style added during drag
				$(ui.helper).removeAttr('style');
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
				elgg.register_error(elgg.echo('forms:compose:conditional_section:invalid_drop'));
			}
		});
	};
	
	var saveDefinition = function() {
		var result = {
			'pages': []
		};
		
		$('.forms-compose-list-page').each(function(page_index, page_element) {
			var page = {
				'title' :  $(page_element).find(' > span').eq(0).text(),
				'sections' : []
			};
			
			$(this).find('> ul > .forms-compose-list-section').each(function(section_index, section_element) {
				var section = {
					'title' : $(section_element).find(' > span').eq(0).text(),
					'fields' : []
				};
				
				$(this).find('> ul > .forms-compose-list-field').each(function(field_index, field_element) {
					var field = $(field_element).data('params');
					field['conditional_sections'] = [];
					
					$(this).find('> .forms-compose-conditional-section').each(function(conditional_section_index, conditional_section_element) {
						var conditional_value = $(conditional_section_element).find('[name="conditional_value"]').val(); 
						var conditional_section = {
							'value' : conditional_value,
							'fields' : []
						};
						
						$(this).find('> ul > .forms-compose-list-field').each(function(conditional_field_index, conditional_field_element) {
							var conditional_field = $(conditional_field_element).data('params');
							conditional_section['fields'].push(conditional_field);
							console.log(conditional_field);
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
	};
	
	
	
	// sortable
	var init = function() {
		
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
		$(document).on('mouseenter', '.forms-compose-list-page > span:first-child, .forms-compose-list-section > span:first-child', addEditTitleLink);
		
	};
	
	elgg.register_hook_handler('init', 'system', init);
	
	
});
