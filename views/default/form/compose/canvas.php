<?php

$entity = elgg_extract('entity', $vars);

$definition = json_decode($entity->definition, true);

$pages = elgg_extract('pages', $definition);
if (empty($pages)) {
	$pages = [
		'sections' => [
			[
				'title' => 'Section A',
			],
		],
		'title' => 'Page 1',
	];
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'definition',
]);

$pages_result = '';
foreach ($pages as $page) {
	
	$sections_result = '';
	foreach (elgg_extract('sections', $page) as $section) {
		$fields_result = '';
		
		foreach (elgg_extract('fields', $section) as $field) {
			$field_body = elgg_format_element('span', [], elgg_extract('title', $field));
			
			$fields_result .= elgg_format_element('li', [
				'class' => 'forms-compose-list-field',
			], $field_body);
		}
				
		$section_body = elgg_format_element('span', [], elgg_extract('title', $section));
		$section_body .= elgg_format_element('ul', [], $fields_result);
		
		$sections_result .= elgg_format_element('li', [
			'class' => 'forms-compose-list-section',
		], $section_body);
	}
	
	$sections_result .= elgg_format_element('li', [], elgg_view_field([
		'#type' => 'button',
		'class' => 'elgg-button-action form-compose-add-section',
		'value' => elgg_echo('add-section'),
	]));

	$page_body = elgg_format_element('span', [], elgg_extract('title', $page));
	$page_body .= elgg_format_element('ul', [], $sections_result);
	
	$pages_result .= elgg_format_element('li', [
		'class' => 'forms-compose-list-page',
	], $page_body);
}


$pages_result .= elgg_format_element('li', [], elgg_view_field([
	'#type' => 'button',
	'class' => 'elgg-button-action form-compose-add-page',
	'value' => elgg_echo('add-page'),
]));

echo elgg_format_element('ul', ['class' => 'forms-compose-list'], $pages_result);
