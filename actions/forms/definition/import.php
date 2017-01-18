<?php

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof Form) || !$entity->canEdit()) {
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

if (!$entity->importDefinition($json)) {
	return elgg_error_response(elgg_echo('forms:action:definition:import:error:json_definition'));
}

return elgg_ok_response('', elgg_echo('forms:action:definition:import:success'), REFERER);
