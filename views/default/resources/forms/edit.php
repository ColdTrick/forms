<?php
/**
 * Edit a form
 */

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', Form::SUBTYPE);
$entity = get_entity($guid);
if (!$entity->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_entity_breadcrumbs($entity);

$body_vars = forms_prepare_form_vars( $entity->getContainerGUID(), $entity);

$content = elgg_view_form('forms/edit', ['prevent_double_submit' => true], $body_vars);

$sidebar = elgg_view('form/sidebar/history', ['entity' => $entity]);

$title = elgg_echo('forms:edit:title', [$entity->getDisplayName()]);

// build page
$page_data = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => false,
	'entity' => $entity,
]);

// draw page
echo elgg_view_page($title, $page_data);
