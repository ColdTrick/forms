<?php
/**
 * List all the forms in the system
 */

elgg_admin_gatekeeper();

// add title buttons
elgg_register_title_button();

// build page elements
$title_text = elgg_echo('forms:all:title');

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => Form::SUBTYPE,
	'no_results' => elgg_echo('notfound'),
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title_text,
	'content' => $content,
	'filter' => '',
]);

// draw page
echo elgg_view_page($title_text, $page_data);
