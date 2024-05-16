<?php
/**
 * List all the forms in the system
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof \ElggGroup) {
	$page_owner = elgg_get_site_entity();
}

if ($page_owner->canWriteToContainer(0, 'object', \Form::SUBTYPE)) {
	elgg_register_title_button('add', 'object', \Form::SUBTYPE);
}

elgg_push_collection_breadcrumbs('object', \Form::SUBTYPE);

echo elgg_view_page(elgg_echo('collection:object:form'), [
	'content' => elgg_view('form/listing/all'),
	'filter_id' => 'forms',
]);
