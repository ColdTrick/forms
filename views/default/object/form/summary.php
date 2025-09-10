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

$imprint = [];
if ($entity->canEdit()) {
	$imprint[] = [
		'icon_name' => 'check-square-regular',
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
	'content' => $entity->description ? elgg_get_excerpt($entity->description) : null,
	'imprint' => $imprint,
	'access' => false,
	'byline_owner_entity' => false,
];
$params = $params + $vars;

echo elgg_view('object/elements/summary', $params);
