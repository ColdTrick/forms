<?php

$rule = elgg_extract('item', $vars);

$summary = '';
$summary .= elgg_view('object/elements/summary/metadata', ['metadata' => elgg_view_menu('validation_rule', [
	'rule' => $rule,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz elgg-menu-entity',
])]);
$summary .= elgg_view('object/elements/summary/title', ['title' => elgg_extract('label', $rule)]);
$summary .= elgg_view('object/elements/summary/subtitle', [
	'subtitle' => elgg_echo('forms:validation_rule:regex:output', [elgg_extract('regex', $rule)]),
]);

echo elgg_view_image_block('', $summary);
