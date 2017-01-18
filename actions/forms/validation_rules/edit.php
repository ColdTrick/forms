<?php

$name = get_input('name');
$label = get_input('label');
$error_message = get_input('error_message');
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
$rule['error_message'] = ($error_message !== '') ? $error_message : null;
$rule['regex'] = $regex;
$rule['input_types'] = $input_types;

$rules = forms_get_validation_rules();
$rules[$name] = $rule;

if (forms_save_validation_rules($rules)) {
	return elgg_ok_response('', elgg_echo('save:success'), REFERER);
}

return elgg_error_response(elgg_echo('save:fail'));
