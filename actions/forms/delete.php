<?php

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof Form)) {
	return elgg_error_response(elgg_echo('entity:delete:item_not_found'));
}

if (!$entity->canDelete()) {
	return elgg_error_response(elgg_echo('entity:delete:permission_denied'));
}

$title = $entity->getDisplayName();
if (!$entity->delete()) {
	return elgg_error_response(elgg_echo('entity:delete:fail', [$title]));
}

return elgg_ok_response('', elgg_echo('entity:delete:success', [$title]), 'forms/all');
