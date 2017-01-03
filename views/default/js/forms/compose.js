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

	var deleteFormElement = function(elem) {	
		// resolves to parent li (which could be Field, Section or Page)
		$(this).parents('li').eq(0).remove();
	};
	
	var editField = function(elem) {
		var $field = $(this).parents('.forms-compose-list-field').eq(0);

		var $form = $field.find('.forms-compose-edit-field');
		// close form if it is visible/expanded
		if ($form.is(':visible')) {
			$form.slideToggle();
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
			$form.find('[name="' + key + '"]').val(value);
		});
		
		// show form
		$form.slideToggle();
	};
	
	var saveField = function(elem) {
		var $field = $(this).parents('.forms-compose-list-field').eq(0);
		
		var $form = $field.find('.forms-compose-edit-field');
		
		var params = $field.data('params');
		
		$.each($form.find('[name]').serializeArray(), function(key, field) {
			params[field.name] = field.value;
		});
		$field.data('params', params);
		
		$field.find('span:first').html($field.data('params').title);
		
		// hide form
		$form.slideToggle();
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
		$('.forms-compose-list-section > ul').sortable({
			axis: 'y',
			connectWith: '.forms-compose-list-section > ul'
		});
	};
	
	var saveDefinition = function() {
		var result = {
			'pages': []
		};
		
		$('.forms-compose-list-page').each(function(page_index, page_element) {
			var page = {
				'title' :  $(page_element).find(' > span').text(),
				'sections' : []
			};
			
			$(this).find('.forms-compose-list-section').each(function(section_index, section_element) {
				var section = {
					'title' : $(section_element).find(' > span').text(),
					'fields' : []
				};
				
				$(this).find('.forms-compose-list-field').each(function(field_index, field_element) {
					var field = $(field_element).data('params');
				
					section['fields'].push(field);
				});
				
				page['sections'].push(section);
			});
			
			result['pages'].push(page);
		});	
		
		result = JSON.stringify(result);
		console.log(result);
		$('.elgg-form-forms-compose input[name="definition"]').val(result);
	};
	
	
	
	// sortable
	var init = function() {
		
		initSortablePages();
		
		// draggable
		$('.forms-compose-fields > li').draggable({
			helper: 'clone',
			connectToSortable: '.forms-compose-list-section > ul'
		});
		
		$(document).on('click', '.forms-compose-add-page', addPage);
		$(document).on('click', '.forms-compose-add-section', addSection);
		$(document).on('click', '.forms-compose-save', saveDefinition);
		$(document).on('click', '.forms-compose-delete', deleteFormElement);
		$(document).on('click', '.forms-compose-field-edit', editField);
		$(document).on('click', '.forms-compose-field-save', saveField);
		
	};
	
	elgg.register_hook_handler('init', 'system', init);
	
	
});
