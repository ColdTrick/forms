<?php
/**
 * Create/edit a form
 *
 * @uses $vars['entity'] (optional) the form to edit
 * @uses $vars['container_guid'] where to place the form
 */

/* @var $entity \Form */
$entity = elgg_extract('entity', $vars);

elgg_require_js('forms/edit');

$footer = '';

// form elements
echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('title'),
	'id' => 'form_title',
	'name' => 'title',
	'value' => elgg_extract('title', $vars),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('forms:edit:friendly_url'),
	'id' => 'friendly_url',
	'name' => 'friendly_url',
	'value' => elgg_extract('friendly_url', $vars),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('description'),
	'name' => 'description',
	'value' => elgg_extract('description', $vars),
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('forms:edit:thankyou'),
	'#help' => elgg_echo('forms:edit:thankyou:help'),
	'name' => 'thankyou',
	'value' => elgg_extract('thankyou', $vars),
]);

if (empty($entity)) {
	echo elgg_view_field([
		'#type' => 'file',
		'#label' => elgg_echo('forms:edit:definition'),
		'name' => 'definition',
	]);
}

echo elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('access'),
	'name' => 'access_id',
	'value' => (int) elgg_extract('access_id', $vars),
	'entity_type' => 'object',
	'entity_subtype' => Form::SUBTYPE,
	'entity' => $entity,
	'container_guid' => elgg_extract('container_guid', $vars),
	'entity_allows_comments' => false,
]);

// endpoint config
echo elgg_view('form/edit/endpoint', $vars);

// footer
if (!empty($entity)) {
	$footer .= elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $entity->getGUID(),
	]);
}
$footer .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
]);
$footer .= elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
