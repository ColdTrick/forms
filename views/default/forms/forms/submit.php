<?php

$entity = elgg_extract('entity', $vars);

$pages = $entity->getDefinition()->getPages();
foreach ($pages as $page) {
	
	$page_body = '';
	foreach ($page->getSections() as $section) {
		
		$section_body = '';
		foreach ($section->getFields() as $field) {
			$section_body .= elgg_view_field($field->getInputVars());
			
			$condition_sections = $field->getConditionalSections();
			if ($condition_sections) {
				$condition_sections_body = '';
				foreach ($condition_sections as $conditional_section) {
					
					$fields = [];
					foreach ($conditional_section->getFields() as $conditional_field) {
						$fields[] = $conditional_field->getInputVars();
					}
					$condition_sections_body .= elgg_view('input/fieldset', [
						'legend' => $conditional_section->getValue(),
						'fields' => $fields,
					]);
				}
				
				$section_body .= $condition_sections_body;
			}
		}
		
		if (empty($section_body)) {
			continue;
		}
						
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
