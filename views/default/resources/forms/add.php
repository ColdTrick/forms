<?php
use Elgg\Exceptions\Http\EntityPermissionsException;

$container = elgg_get_page_owner_entity();
if (!$container->canWriteToContainer(0, 'object', \Form::SUBTYPE)) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', \Form::SUBTYPE);

echo elgg_view_page(elgg_echo('forms:add:title'), [
	'content' => elgg_view_form('forms/edit', ['sticky_enabled' => true]),
	'sidebar' => false,
	'filter_id' => 'forms/add',
]);
