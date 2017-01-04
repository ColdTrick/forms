<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', \Form::SUBTYPE);

$entity = get_entity($guid);

$title = $entity->getDisplayName();

$content = elgg_view_form('forms/compose', [], ['entity' => $entity]);

$body = elgg_view_layout('forms_compose', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
