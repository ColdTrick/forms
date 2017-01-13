<?php

elgg_admin_gatekeeper();

elgg_push_breadcrumb(elgg_echo('forms:all:title'), 'forms/all');

// create add button
elgg_register_menu_item('title', [
	'name' => 'add',
	'text' => elgg_echo('add'),
	'href' => 'ajax/form/forms/validation_rules/edit',
	'link_class' => [
		'elgg-button',
		'elgg-button-action',
		'elgg-lightbox',
	],
	'deps' => 'elgg/lightbox',
]);

// build page elements
$title = elgg_echo('forms:validation_rules:title');

$body = elgg_view('form/validation_rules/list', [
	'rules' => forms_get_validation_rules(),
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
