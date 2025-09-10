<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \Form) {
	return;
}

elgg_import_esm('forms/forms/compose');

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

echo elgg_view('form/compose/canvas', $vars);
echo elgg_view('form/compose/edit_field', $vars);
echo elgg_view('form/compose/edit_conditional_section', $vars);

// empty page for js inclusion
$template_page = elgg_view('form/compose/canvas/page', [
	'page' => [
		'title' => elgg_echo('forms:compose:page:new'),
		'sections' => [
			[
				'title' => elgg_echo('forms:compose:section:new'),
			]
		],
	],
]);

echo elgg_format_element('ul', [
	'id' => 'forms-compose-page-template',
	'class' => 'hidden',
], $template_page);

$footer = elgg_view_field([
	'#type' => 'button', // don't use submit to prevent submit on enter keydown
	'icon' => 'save',
	'class' => 'elgg-button-submit forms-compose-save',
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
