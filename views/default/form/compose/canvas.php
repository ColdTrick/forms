<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \Form) {
	return;
}

$definition = $entity->definition ? json_decode($entity->definition, true) : [];

$pages = elgg_extract('pages', $definition);
if (empty($pages)) {
	$pages = [
		[
			'sections' => [
				[
					'title' => 'Section A',
				],
			],
			'title' => 'Page 1',
		]
	];
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'definition',
]);

$pages_result = '';
foreach ($pages as $page) {
	$pages_result .= elgg_view('form/compose/canvas/page', ['page' => $page]);
}

$pages_result .= elgg_format_element('li', [], elgg_view_field([
	'#type' => 'button',
	'icon' => 'plus',
	'class' => 'elgg-button-action forms-compose-add-page',
	'text' => elgg_echo('forms:compose:page:add'),
]));

echo elgg_format_element('ul', ['class' => 'forms-compose-list'], $pages_result);
