<?php
/**
 * Add a form
 */

$guid = (int) elgg_extract('guid', $vars);

$container = get_entity($guid);
if (!($container instanceof ElggGroup)) {
	$container = elgg_get_site_entity();
}

elgg_push_collection_breadcrumbs('object', 'form', $container);
elgg_push_breadcrumb(elgg_echo('add'));
	
$title = elgg_echo('forms:add:title');

$body_vars = forms_prepare_form_vars($guid);

$content = elgg_view_form('forms/edit', ['prevent_double_submit' => true], $body_vars);

// build page
$page_data = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'sidebar' => false,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
