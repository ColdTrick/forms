<?php
/**
 * List all the forms in the system
 */

elgg_register_title_button('forms', 'add', 'object', 'form');

elgg_push_collection_breadcrumbs('object', 'form');

// build page elements
$title_text = elgg_echo('collection:object:form');

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => Form::SUBTYPE,
	'no_results' => true,
]);

// build page
$page_data = elgg_view_layout('default', [
	'title' => $title_text,
	'content' => $content,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title_text, $page_data);
