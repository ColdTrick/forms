<?php

$name = get_input('name');
if (empty($name)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$rules = forms_get_validation_rules();
if (empty($rules) || !isset($rules[$name])) {
	return elgg_error_response(elgg_echo('entity:delete:item_not_found'));
}

$label = elgg_extract('label', $rules[$name]);

unset($rules[$name]);

if (elgg_set_plugin_setting('validation_rules', json_encode($rules), 'forms')) {
	return elgg_ok_response('', elgg_echo('entity:delete:success', [$label]), REFERER);
}

return elgg_error_response(elgg_echo('entity:delete:fail', [$label]));
