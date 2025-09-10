<?php
/**
 * View a form
 *
 * @uses $vars['entity']    the form entity
 * @uses $vars['full_view'] show full view or listing
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \Form) {
	return;
}

// @TODO make this
echo $entity->title;
