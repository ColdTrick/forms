<?php

$rules = forms_get_validation_rules();
if (empty($rules)) {
	return;
}

$options_values = [
	'' => elgg_echo('forms:validation_rule:input:none'),
];
foreach ($rules as $name => $rule) {
	$options_values[$name] = elgg_extract('label', $rule);
}

$vars['options_values'] = $options_values;

echo elgg_view('input/select', $vars);
