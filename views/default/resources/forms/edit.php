<?php

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', \Form::SUBTYPE, true);

/* @var $entity \Form */
$entity = get_entity($guid);

elgg_push_collection_breadcrumbs('object', 'form', $entity->getContainerEntity());

echo elgg_view_page(elgg_echo('forms:edit:title', [$entity->getDisplayName()]), [
	'content' => elgg_view_form('forms/edit', ['prevent_double_submit' => true], ['entity' => $entity]),
	'sidebar' => elgg_view('form/sidebar/history', ['entity' => $entity]),
	'filter_id' => 'forms/edit',
	'entity' => $entity,
]);
