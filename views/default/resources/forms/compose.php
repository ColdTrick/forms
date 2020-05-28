<?php

// validate input
$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', \Form::SUBTYPE);

/* @var $entity \Form */
$entity = get_entity($guid);
if (!$entity->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

// breadcrumb
elgg_push_entity_breadcrumbs($entity);

// import/export
elgg_register_menu_item('title', [
	'name' => 'import',
	'icon' => 'upload',
	'text' => elgg_echo('import'),
	'href' => "ajax/form/forms/definition/import?guid={$entity->getGUID()}",
	'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
	'data-colorbox-opts' => json_encode([
		'maxWidth' => '600px;',
	]),
]);
if ($entity->hasDefinition()) {
	elgg_register_menu_item('title', [
		'name' => 'export',
		'icon' => 'download',
		'text' => elgg_echo('export'),
		'href' => elgg_generate_action_url('forms/definition/export', ['guid' => $guid]),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

// build page elements
$title = elgg_echo('forms:compose:title', [$entity->getDisplayName()]);

$content = elgg_view('form/compose/validation', ['entity' => $entity]);
$content .= elgg_view_form('forms/compose', ['prevent_double_submit' => true], ['entity' => $entity]);

// build page
elgg_push_context('compose');

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'entity' => $entity,
	'filter' => false,
	'sidebar' => elgg_view('form/compose/fields', ['entity' => $entity]),
	'show_owner_block_menu' => false,
]);

elgg_pop_context();

// draw page
echo elgg_view_page($title, $body);
