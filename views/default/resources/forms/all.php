<?php
/**
 * List all the forms in the system
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggGroup) {
	$page_owner = elgg_get_site_entity();
}
if ($page_owner->canWriteToContainer(0, 'object', 'form')) {
	elgg_register_title_button('forms', 'add', 'object', 'form');
}

elgg_push_collection_breadcrumbs('object', 'form');

// build page elements
$title_text = elgg_echo('collection:object:form');

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => Form::SUBTYPE,
	'no_results' => true,
]);

// draw page
echo elgg_view_page($title_text, [
	'content' => $content,
]);
