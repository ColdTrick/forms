<?php

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof Form)) {
	return elgg_error_response(elgg_echo('noaccess'));
}

$text = get_input('json_text');
$file = get_uploaded_file('json_file');

if (empty($text) && empty($file)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$json = $text;
if (!empty($file)) {
	// file import wins over raw text
	$json = $file;
}

$data = @json_decode($json, true);
if (empty($data)) {
	return elgg_error_response(elgg_echo('forms:action:definition:import:error:json_format'));
}

$definition = elgg_extract('definition', $data);
if (empty($definition)) {
	return elgg_error_response(elgg_echo('forms:action:definition:import:error:json_definition'));
}

$entity->definition = json_encode($definition);

$validation_rules = elgg_extract('rules', $data);
if (!empty($validation_rules)) {
	// proccess validation rules
	$current_validation_rules = forms_get_validation_rules();
	
	foreach ($validation_rules as $name => $rule) {
		if (array_key_exists($name, $current_validation_rules)) {
			// don't override already existing validation rules
			continue;
		}
		
		$current_validation_rules[$name] = $rule;
	}
	
	forms_save_validation_rules($current_validation_rules);
}

return elgg_ok_response('', elgg_echo('forms:action:definition:import:success'), REFERER);
