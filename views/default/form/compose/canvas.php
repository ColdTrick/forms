<?php

$entity = elgg_extract('entity', $vars);

$definition = json_decode($entity->definition, true);

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
	'class' => 'elgg-button-action forms-compose-add-page',
	'value' => elgg_echo('forms:compose:page:add'),
]));

echo elgg_format_element('ul', ['class' => 'forms-compose-list'], $pages_result);
