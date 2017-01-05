<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', \Form::SUBTYPE);

$entity = get_entity($guid);

$title = $entity->getDisplayName();

$body = elgg_view('output/longtext', [
	'value' => $entity->description,
]);
$body .= elgg_view_form('forms/submit', [], ['entity' => $entity]);

$body = elgg_view_layout('one_column', [
	'title' => $title,
	'content' => $body,
]);

echo elgg_view_page($title, $body);
