<?php

$rules = elgg_extract('rules', $vars);
if (empty($rules)) {
	echo elgg_echo('forms:validation_rules:none');
	return;
}

$items = [];
foreach ($rules as $name => $rule) {
	
	$output = elgg_view('form/validation_rules/item', [
		'item' => $rule,
	]);
	if (empty($output)) {
		continue;
	}
	
	$items[] = elgg_format_element('li', ['class' => 'elgg-list-item'], $output);
}

if (empty($items)) {
	echo elgg_echo('forms:validation_rules:none');
	return;
}

echo elgg_format_element('ul', ['class' => 'elgg-list'], implode('', $items));
