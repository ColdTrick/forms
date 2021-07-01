<?php
/**
 * Edit a form
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', Form::SUBTYPE);
$entity = get_entity($guid);
if (!$entity->canEdit()) {
	throw new EntityPermissionsException();
}

elgg_push_entity_breadcrumbs($entity);

$body_vars = forms_prepare_form_vars( $entity->getContainerGUID(), $entity);

$content = elgg_view_form('forms/edit', ['prevent_double_submit' => true], $body_vars);

$sidebar = elgg_view('form/sidebar/history', ['entity' => $entity]);

// draw page
echo elgg_view_page(elgg_echo('forms:edit:title', [$entity->getDisplayName()]), [
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => false,
	'entity' => $entity,
]);
