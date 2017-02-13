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
$page_count = 1;
foreach ($pages as $page_index => $page) {
	
	// sections
	$page_body = '';
	foreach ($page->getSections() as $section) {
		
		// fields
		$section_body = '';
		foreach ($section->getFields() as $field) {
			$field_vars = [
				'sticky_value' => elgg_extract($field->getName(), $sticky_values),
			];
			
			$condition_sections = $field->getConditionalSections();
			if (!empty($condition_sections)) {
				$field_vars['class'] = 'forms-submit-conditional';
			}
			
			$section_body .= elgg_view_field($field->getInputVars($field_vars));
			
			// conditional sections
			if (!empty($condition_sections)) {
				$condition_sections_body = '';
				foreach ($condition_sections as $conditional_section) {
					
					$conditional_section_value = $conditional_section->getValue();
					if ($conditional_section_value === null) {
						// only show conditional section with a value to check
						continue;
					}
					
					// should this section be hidden, from sticky form value
					$hide_section = true;
					if ($field_vars['sticky_value'] === $conditional_section_value) {
						$hide_section = false;
					}
					
					// fields of the conditional section
					$fields = [];
					foreach ($conditional_section->getFields() as $conditional_field) {
						$conditional_field_vars = [
							'sticky_value' => elgg_extract($conditional_field->getName(), $sticky_values),
							'disabled' => $hide_section,
						];
						
						$fields[] = $conditional_field->getInputVars($conditional_field_vars);
					}
					
					if (empty($fields)) {
						// no conditional section if no fields
						continue;
					}
					
					$condition_sections_body .= elgg_view('input/fieldset', [
						'fields' => $fields,
						'class' => $hide_section ? 'hidden' : '',
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
		'text' => elgg_echo('forms:submit:tab:text', [$page_count]),
		'title' => $page->getTitle(),
		'content' => $page_body,
		'selected' => $page_count === 1,
		'class' => ($page_count > 2 && empty($sticky_values)) ? 'elgg-state-disabled' : '',
	];
	$page_count++;
}

$footer = '';
if (count($tabs) === 1) {
	echo $tabs[0]['content'];
	
	$footer .= elgg_view_field([
		'#type' => 'submit',
		'value' => elgg_echo('submit'),
	]);
} else {
	
	foreach ($tabs as $index => $tab) {
		$buttons = [];
		
		// next button
		if (($index + 1) < count($tabs)) {
			$buttons[] = [
				'#type' => 'button',
				'#class' => [
					'float-alt',
				],
				'class' => [
					'elgg-button-submit',
					'forms-submit-buttons-next',
				],
				'value' => elgg_echo('next'),
			];
		}
		
		// submit button
		if (($index + 1) === count($tabs)) {
			$buttons[] = [
				'#type' => 'submit',
				'#class' => [
					'float-alt',
				],
				'value' => elgg_echo('submit'),
			];
		}
		
		// prev button
		if ($index > 0) {
			$buttons[] = [
				'#type' => 'button',
				'class' => [
					'elgg-button-action',
					'forms-submit-buttons-prev',
				],
				'value' => elgg_echo('previous'),
			];
		}
		
		$tabs[$index]['content'] .= elgg_view('input/fieldset', [
			'class' => 'forms-submit-buttons',
			'fields' => $buttons,
			'align' => 'horizontal',
		]);
	}
	
	echo elgg_view('page/components/tabs', ['tabs' => $tabs]);
}

// build footer
$footer .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'form_guid',
	'value' => $entity->getGUID(),
]);

elgg_set_form_footer($footer);
