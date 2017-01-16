<?php

// validate input
$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', \Form::SUBTYPE);

/* @var $entity \Form */
$entity = get_entity($guid);

// breadcrumb
elgg_push_breadcrumb(elgg_echo('forms:all:title'), 'forms/all');
elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());

// import/export
elgg_register_menu_item('title', [
	'name' => 'import',
	'text' => elgg_echo('import'),
	'href' => "ajax/form/forms/definition/import?guid={$entity->getGUID()}",
	'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
	'deps' => 'elgg/lightbox',
	'data-colorbox-opts' => json_encode([
		'maxWidth' => '600px;',
	]),
]);
if ($entity->hasDefinition()) {
	elgg_register_menu_item('title', [
		'name' => 'export',
		'text' => elgg_echo('export'),
		'href' => "action/forms/definition/export?guid={$entity->getGUID()}",
		'link_class' => 'elgg-button elgg-button-action',
		'is_action' => true,
	]);
}

// build page elements
$title = elgg_echo('forms:compose:title', [$entity->getDisplayName()]);

$content = elgg_view_form('forms/compose', [], ['entity' => $entity]);

// build page
$body = elgg_view_layout('forms_compose', [
	'title' => $title,
	'content' => $content,
]);

// draw page
echo elgg_view_page($title, $body);
