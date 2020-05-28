<?php
/**
 * Show validation messages for CSV forms
 *
 * @uses $vars['entity'] the form entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof Form) {
	return;
}

// already have data?
$endpoint = $entity->getEndpoint();
$file = $endpoint->getFile($entity);
if ($file->exists()) {
	echo elgg_view_message('warning', elgg_echo('form:endpoint:csv:validation:file_exists'));
}

$definition = $entity->getDefinition();
if (!$definition->isValid()) {
	$errors = $definition->getValidationErrors();
	foreach ($errors as $error) {
		echo elgg_view_message('error', $error);
	}
}