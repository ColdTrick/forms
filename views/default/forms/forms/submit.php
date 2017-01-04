<?php

$entity = elgg_extract('entity', $vars);

$config = $entity->getDefinition()->getConfig();

$pages = elgg_extract('pages', $config, []);
foreach ($pages as $page) {
	echo elgg_format_element('h3', [], elgg_extract('title', $page));
	
	$sections = elgg_extract('sections', $page, []);
	foreach ($sections as $section) {
		
		$fields = elgg_extract('fields', $section, []);
		foreach ($fields as $index => $field) {
			
			$field['#type'] = $field['type'];
			unset($field['type']);
			
			$field['#label'] = $field['label'];
			unset($field['label']);
			
			
			$fields[$index] = $field;
		}
		
		$section_body = elgg_view('input/fieldset', [
			'fields' => $fields,
		]);
		
		echo elgg_view_module('info', elgg_extract('title', $section), $section_body);
	}
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('submit'),
]);

elgg_set_form_footer($footer);
