<?php

$types = [
	'text',
	'longtext',
	'radio',
	'pulldown',
];

$list = '';
foreach ($types as $type) {
	$type_body = elgg_format_element('span', [], $type);
	$list .= elgg_format_element('li', ['class' => 'forms-compose-list-field'], $type_body);
}

echo elgg_format_element('ul', ['class' => 'forms-compose-fields'], $list);
