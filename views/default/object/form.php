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

if ($full_view) {
	// @TODO make this
	echo $entity->title;
} else {
	
	$imprint = [];
	if ($entity->canEdit()) {
		$imprint[] = [
			'icon_name' => 'check-square-o',
			'content' => elgg_format_element('span', [
				'title' => elgg_echo('forms:by_line:submissions'),
				'class' => 'forms-submissions',
			], (int) $entity->submitted_count),
		];
	}
	
	if (!$entity->isValid()) {
		$imprint[] = [
			'icon_name' => 'exclamation-circle',
			'content' => elgg_echo('forms:definition:validation:error:imprint'),
			'class' => 'elgg-state elgg-state-error',
		];
	}
	
	$params = [
		'entity' => $entity,
		'content' => elgg_get_excerpt($entity->description),
		'imprint' => $imprint,
		'access' => false,
		'byline_owner_entity' => false,
	];
	
	echo elgg_view('object/elements/summary', $params);
}
