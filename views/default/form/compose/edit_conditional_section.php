<?php

$output = elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('forms:compose:conditional_section:value:label'),
	'name' => 'value',
]);

$placeholder = elgg_format_element('li', ['class' => 'forms-field-unsortable'], elgg_echo('forms:compose:conditional_section:placeholder'));

$output .= elgg_format_element('ul', [], $placeholder);

echo elgg_format_element('div', [
	'id' => 'forms-compose-conditional-section',
	'class' => 'forms-compose-conditional-section hidden',
], $output);
