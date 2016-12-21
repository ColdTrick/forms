<?php

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof Form) || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$definition = get_input('definition');
if (empty($definition)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity->definition = $definition;

return elgg_ok_response();
