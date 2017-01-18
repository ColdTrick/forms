<?php

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof Form) || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('noaccess'));
}

if (!$entity->hasDefinition()) {
	return elgg_error_response(elgg_echo('forms:action:definition:error:no_def'));
}

$definition = $entity->exportDefinition();
if (empty($definition)) {
	return elgg_error_response(elgg_echo('forms:action:definition:error:no_def'));
}

header('Content-Type: application/json');
header('Content-Length: ' . strlen($definition));
header('Content-Disposition: attachment; filename="' . elgg_get_friendly_title($entity->getDisplayName()) . '-definition.json"');
header('Pragma: public');

echo $definition;
exit();
