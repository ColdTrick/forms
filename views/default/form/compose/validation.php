<?php
/**
 * Show validation messages about the current form
 *
 * @uses $vars['entity'] the form entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof Form) {
	return;
}

$endpoint = $entity->endpoint;
if (elgg_view_exists("form/compose/validation/{$endpoint}")) {
	echo elgg_view("form/compose/validation/{$endpoint}", $vars);
}
