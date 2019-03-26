<?php

elgg_ajax_gatekeeper();

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Form)) {
	return;
}

$title = elgg_echo('forms:import:title');
$content = '';

if ($entity->hasDefinition()) {
	$content .= elgg_view_message('warning', elgg_echo('forms:import:warning:definition'));
}

$content .= elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('forms:import:json_file'),
	'name' => 'json_file',
]);

$content .= elgg_format_element('div', [], elgg_echo('forms:import:or'));

$content .= elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('forms:import:json_text'),
	'#help' => elgg_echo('forms:import:json_text:help'),
	'name' => 'json_text',
	'rows' => '3',
]);

echo elgg_view_module('info', $title, $content);

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