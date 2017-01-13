<?php

$rule = elgg_extract('item', $vars);

$menu = elgg_view_menu('validation_rule', [
	'rule' => $rule,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz elgg-menu-entity',
]);

$title = elgg_view('object/elements/summary/title', [
	'title' => elgg_extract('label', $rule),
]);
$subtitle = elgg_view('object/elements/summary/subtitle', [
	'subtitle' => elgg_echo('forms:validation_rule:regex:output', [elgg_extract('regex', $rule)]),
]);

$content = $menu . $title . $subtitle;

echo elgg_view_image_block('', $content);
