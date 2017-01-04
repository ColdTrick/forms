<?php

$entity = elgg_extract('entity', $vars);

$pages = $entity->getDefinition()->getPages();
foreach ($pages as $page) {
	
	$page_body = '';
	foreach ($page->getSections() as $section) {
		
		$fields = [];
		foreach ($section->getFields() as $field) {
			$fields[] = $field->getInputVars();
		}
		
		if (empty($fields)) {
			continue;
		}
		
		$section_body = elgg_view('input/fieldset', [
			'fields' => $fields,
		]);
		
		$page_body .= elgg_view_module('info', $section->getTitle(), $section_body);
	}
	
	if (empty($page_body)) {
		continue;
	}
	
	echo elgg_format_element('h3', [], $page->getTitle());
	echo $page_body;
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('submit'),
]);

elgg_set_form_footer($footer);
