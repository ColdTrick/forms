<?php

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof Form) || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$new_entity = clone $entity;
if (!$new_entity->save()) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('save:success'), elgg_generate_entity_url($new_entity, 'edit'));
