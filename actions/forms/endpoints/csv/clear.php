<?php
use ColdTrick\Forms\Endpoint\Csv;

/**
 * Clear all csv form results
 */

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!$entity instanceof Form || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$endpoint = $entity->getEndpoint();
if (!$endpoint instanceof Csv) {
	return elgg_error_response(elgg_echo('forms:action:endpoints:csv:clear:error:endpoint'));
}

$file = $endpoint->getFile($entity);
if (!$file->exists()) {
	return elgg_error_response(elgg_echo('forms:action:endpoints:csv:clear:error:no_file'));
}

if (!$file->delete()) {
	return elgg_error_response(elgg_echo('forms:action:endpoints:csv:clear:error:delete_file'));
}

// reset counter
unset($entity->submitted_count);

return elgg_ok_response('', elgg_echo('forms:action:endpoints:csv:clear:success'));
