<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \Form)) {
	return;
}

elgg_require_js('forms/submit');

$sticky_values = (array) elgg_extract('sticky_values', $vars, []);

// draw pages
$pages = $entity->getDefinition()->getPages();
$tabs = [];
foreach ($pages as $page_index => $page) {
	
	// sections
	$page_body = '';
	foreach ($page->getSections() as $section) {
		
		// fields
		$section_body = '';
		foreach ($section->getFields() as $field) {
			$additional_vars = [
				'sticky_value' => elgg_extract($field->getName(), $sticky_values),
			];
			
			$condition_sections = $field->getConditionalSections();
			if ($condition_sections) {
				$additional_vars['class'] = 'forms-submit-conditional';
			}
			
			$section_body .= elgg_view_field($field->getInputVars($additional_vars));
			
			// conditional sections
			
			if ($condition_sections) {
				$condition_sections_body = '';
				foreach ($condition_sections as $conditional_section) {
					
					$conditional_section_value = $conditional_section->getValue();
					if ($conditional_section_value === null) {
						// only show conditional section with a value to check
						continue;
					}
					
					// fields of the conditional section
					$fields = [];
					foreach ($conditional_section->getFields() as $conditional_field) {
						$additional_vars = [
							'sticky_value' => elgg_extract($conditional_field->getName(), $sticky_values),
						];
						
						$fields[] = $conditional_field->getInputVars($additional_vars);
					}
					
					if (empty($fields)) {
						// no conditional section if no fields
						continue;
					}
					
					$condition_sections_body .= elgg_view('input/fieldset', [
						'legend' => $conditional_section->getValue(),
						'fields' => $fields,
						'class' => 'hidden',
						'data-conditional-field' => $field->getName(),
						'data-conditional-value' => $conditional_section_value,
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
	
	$tabs[] = [
		'text' => $page->getTitle(),
		'content' => $page_body,
		'selected' => $page_index === 0,
	];
}

if (count($tabs) === 1) {
	echo elgg_format_element('h3', [], $tabs[0]['text']);
	echo $tabs[0]['content'];
} else {
	echo elgg_view('page/components/tabs', ['tabs' => $tabs]);
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
