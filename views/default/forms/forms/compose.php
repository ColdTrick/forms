<?php

elgg_require_js('forms/compose');

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

echo elgg_view('form/compose/canvas', $vars);

echo elgg_view('form/compose/edit_field', $vars);
echo elgg_view('form/compose/edit_conditional_section', $vars);

$footer = elgg_view_field([
	'#type' => 'button', // don't use submit to prevent submit on enter keydown
	'class' => 'elgg-button-submit forms-compose-save',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
