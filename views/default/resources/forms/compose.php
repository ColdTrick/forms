<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', \Form::SUBTYPE);

$entity = get_entity($guid);

elgg_push_breadcrumb(elgg_echo('forms:all:title'), 'forms/all');
elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());

$title = elgg_echo('forms:compose:title', [$entity->getDisplayName()]);

$content = elgg_view_form('forms/compose', [], ['entity' => $entity]);

$body = elgg_view_layout('forms_compose', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
