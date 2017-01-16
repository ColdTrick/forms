<?php

elgg_ajax_gatekeeper();

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Form)) {
	return;
}

$title = elgg_echo('forms:import:title');
$content = '';

if ($entity->hasDefinition()) {
	$content .= elgg_format_element('div', ['class' => 'elgg-message elgg-state-error'], elgg_echo('forms:import:warning:definition'));
}

echo elgg_view_module('info', $title, $content);

// Get post_max_size and upload_max_filesize
$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');

// Determine the correct value
$max_upload = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;

echo elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('forms:import:json_file'),
	'#help' => elgg_echo('forms:file:upload_limit', [elgg_format_bytes($max_upload)]),
	'name' => 'json_file',
]);

echo elgg_format_element('div', [], elgg_echo('forms:import:or'));

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('forms:import:json_text'),
	'#help' => elgg_echo('forms:import:json_text:help'),
	'name' => 'json_text',
	'rows' => '3',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('import'),
]);
$footer .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->getGUID(),
]);

elgg_set_form_footer($footer);
?>
<script type="text/javascript">
	$('.elgg-form-forms-definition-import').prop('enctype', 'multipart/form-data');
</script>