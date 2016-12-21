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
					var field = {
						'title' :  $(field_element).find(' > span').text()
					};
					
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
		
		$(document).on('click', '.form-compose-add-page', addPage);
		$(document).on('click', '.form-compose-add-section', addSection);
		$(document).on('click', '.form-compose-save', saveDefinition);
		
	};
	
	elgg.register_hook_handler('init', 'system', init);
	
	
});
