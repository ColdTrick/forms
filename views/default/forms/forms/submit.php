<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \Form)) {
	return;
}

$sticky_values = (array) elgg_extract('sticky_values', $vars, []);

// draw pages
$pages = $entity->getDefinition()->getPages();
foreach ($pages as $page) {
	
	// sections
	$page_body = '';
	foreach ($page->getSections() as $section) {
		
		// fields
		$section_body = '';
		foreach ($section->getFields() as $field) {
			$sticky_value = elgg_extract($field->getName(), $sticky_values);
			
			$section_body .= elgg_view_field($field->getInputVars($sticky_value));
			
			// conditional sections
			$condition_sections = $field->getConditionalSections();
			if ($condition_sections) {
				$condition_sections_body = '';
				foreach ($condition_sections as $conditional_section) {
					
					// fields of the conditional section
					$fields = [];
					foreach ($conditional_section->getFields() as $conditional_field) {
						$sticky_value = elgg_extract($conditional_field->getName(), $sticky_values);
						
						$fields[] = $conditional_field->getInputVars($sticky_value);
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

// build footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('submit'),
]);
$footer .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'form_guid',
	'value' => $entity->getGUID(),
]);

elgg_set_form_footer($footer);
