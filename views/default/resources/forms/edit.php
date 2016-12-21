<?php
/**
 * Add/edit a form
 */

$guid = (int) elgg_extract('guid', $vars);
$container_guid = (int) elgg_extract('container_guid', $vars);

// process input
$entity = false;
if (!empty($guid)) {
	elgg_entity_gatekeeper($guid, 'object', Form::SUBTYPE);
	
	$entity = get_entity($guid);
	$container_guid = $entity->getContainerGUID();
}

$container = get_entity($container_guid);
if (!($container instanceof ElggGroup)) {
	$container = elgg_get_site_entity();
	$container_guid = $container->getGUID();
}

elgg_require_js('forms/edit');

// page owner
elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb(elgg_echo('forms:all:title'), 'forms/all');

// build page elements
if (!empty($entity)) {
	$title_text = elgg_echo('forms:edit:title', [$entity->getDisplayName()]);
} else {
	$title_text = elgg_echo('forms:add:title');
}

$body_vars = forms_prepare_form_vars($container_guid, $entity);

$content = elgg_view_form('forms/edit', [], $body_vars);

$sidebar = elgg_view('form/sidebar/history', ['entity' => $entity]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title_text,
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => '',
]);

// draw page
echo elgg_view_page($title_text, $page_data);
