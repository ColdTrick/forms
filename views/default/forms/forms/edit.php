<?php
/**
 * Create/edit a form
 *
 * @uses $vars['entity'] (optional) the form to edit
 * @uses $vars['container_guid'] where to place the form
 */

/* @var $entity \Form */
$entity = elgg_extract('entity', $vars);

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

if (empty($entity)) {
	// Get post_max_size and upload_max_filesize
	$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
	$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');
	
	// Determine the correct value
	$max_upload = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;
	
	echo elgg_view_field([
		'#type' => 'file',
		'#label' => elgg_echo('forms:edit:definition'),
		'#help' => elgg_echo('forms:file:upload_limit', [elgg_format_bytes($max_upload)]),
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
// @todo this needs to be extended with more options
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'endpoint',
	'value' => 'email',
]);

echo elgg_view('form/edit/endpoint/email', $vars);

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
