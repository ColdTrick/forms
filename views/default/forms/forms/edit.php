<?php
/**
 * Create/edit a form
 *
 * @uses $vars['entity'] (optional) the form to edit
 * @uses $vars['container_guid'] where to place the form
 */

/* @var $entity \Form */
$entity = elgg_extract('entity', $vars);

elgg_import_esm('forms/forms/edit');

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

echo elgg_view('entity/edit/header', [
	'entity' => $entity,
	'entity_type' => 'object',
	'entity_subtype' => 'form',
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('forms:edit:thankyou'),
	'#help' => elgg_echo('forms:edit:thankyou:help'),
	'name' => 'thankyou',
	'value' => elgg_extract('thankyou', $vars),
]);

if (!$entity instanceof \Form) {
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
	'entity_subtype' => \Form::SUBTYPE,
	'entity' => $entity,
	'container_guid' => elgg_extract('container_guid', $vars),
	'entity_allows_comments' => false,
]);

$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('forms:edit:max_file_size'),
	'#help' => elgg_echo('forms:edit:max_file_size:help', [elgg_format_bytes($post_max_size)]),
	'name' => 'max_file_size',
	'value' => elgg_extract('max_file_size', $vars),
	'pattern' => '^\d+[kmgKMG]?$',
]);

// endpoint config
echo elgg_view('form/edit/endpoint', $vars);

// footer
$footer = '';
if ($entity instanceof \Form) {
	$footer .= elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $entity->guid,
	]);
}

$footer .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
]);

$footer .= elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
