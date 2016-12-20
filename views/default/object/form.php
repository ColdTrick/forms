<?php
/**
 * View a form
 *
 * @uses $vars['entity']    the form entity
 * @uses $vars['full_view'] show full view or listing
 */

/* @var $entity Form */
$entity = elgg_extract('entity', $vars);
$full_view = (bool) elgg_extract('full_view', $vars);

$icon = '';

$entity_menu = '';
if (!elgg_in_context('widgets')) {
	$entity_menu = elgg_view_menu('entity', [
		'entity' => $entity,
		'handler' => 'forms',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

if ($full_view) {
	// @TODO make this
	
} else {
	
	$params = [
		'entity' => $entity,
		'metadata' => $entity_menu,
		'subtitle' => elgg_view('object/form/by_line', $vars),
		'content' => elgg_get_excerpt($entity->description),
	];
	
	$content = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($icon, $content);
}
