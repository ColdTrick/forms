<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', \Form::SUBTYPE);

$entity = get_entity($guid);
$sticky_values = elgg_get_sticky_values("forms_{$guid}");

// build page elements
$title = $entity->getDisplayName();

$body = elgg_view('output/longtext', [
	'value' => $entity->description,
	'class' => 'mbm',
]);

$form_vars = [
	'enctype' => 'multipart/form-data',
	'prevent_double_submit' => true
];
$body_vars = [
	'entity' => $entity,
	'sticky_values' => $sticky_values,
];
$body .= elgg_view_form('forms/submit', $form_vars, $body_vars);

// clear sticky values
elgg_clear_sticky_form("forms_{$guid}");

// draw page
echo elgg_view_page($title, [
	'content' => $body,
]);
