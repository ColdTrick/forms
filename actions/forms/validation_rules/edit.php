<?php

$name = get_input('name');
$label = get_input('label');
$regex = get_input('regex');
$input_types = (array) get_input('input_types', []);

if (empty($label) || empty($regex)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$valid = @preg_match('/' . $regex . '/', null);
if ($valid === false) {
	return elgg_error_response(elgg_echo('forms:action:validation_rules:edit:error:regex'));
}

$rule = [];
if (!empty($name)) {
	$found_rule = forms_get_validation_rule($name);
	if (empty($found_rule)) {
		return elgg_error_response(elgg_echo('save:fail'));
	}
	
	$rule = $found_rule;
} else {
	$name = 'rule-' . substr(md5(microtime(true)), 0, 6);
	while (forms_get_validation_rule($name) !== false) {
		// make sure it's unique
		$name = 'rule-' . substr(md5(microtime(true)), 0, 6);
	}
}

$rule['name'] = $name;
$rule['label'] = $label;
$rule['regex'] = $regex;
$rule['input_types'] = $input_types;

$rules = forms_get_validation_rules();
$rules[$name] = $rule;

if (elgg_set_plugin_setting('validation_rules', json_encode($rules), 'forms')) {
	return elgg_ok_response('', elgg_echo('save:success'), REFERER);
}

return elgg_error_response(elgg_echo('save:fail'));
