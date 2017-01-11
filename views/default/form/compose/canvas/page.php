<?php

$page = elgg_extract('page', $vars);

$sections_result = '';
foreach (elgg_extract('sections', $page) as $section) {
	$sections_result .= elgg_view('form/compose/canvas/section', ['section' => $section]);
}

$sections_result .= elgg_format_element('li', [], elgg_view_field([
	'#type' => 'button',
	'class' => 'elgg-button-action forms-compose-add-section',
	'value' => elgg_echo('forms:compose:section:add'),
]));

$page_title = elgg_view('output/url', [
	'link' => false,
	'class' => 'float-alt link forms-compose-delete',
	'text' => elgg_view_icon('delete'),
	'title' => elgg_echo('delete'),
]);
$page_title .= elgg_extract('title', $page);

$page_body = elgg_format_element('span', [], $page_title);
$page_body .= elgg_format_element('ul', [], $sections_result);

echo elgg_format_element('li', [
	'class' => 'forms-compose-list-page',
], $page_body);
