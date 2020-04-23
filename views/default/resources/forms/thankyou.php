<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', Form::SUBTYPE);

/* @var $entity Form */
$entity = get_entity($guid);

elgg_set_page_owner_guid($entity->getContainerGUID());

// build page element
$title = elgg_echo('forms:thankyou:title', [$entity->getDisplayName()]);

$content = elgg_view('form/thankyou', [
	'entity' => $entity,
]);

// draw page
echo elgg_view_page($title, [
	'content' => $content,
]);
