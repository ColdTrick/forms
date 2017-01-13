<?php

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof Form)) {
	return elgg_error_response(elgg_echo('noaccess'));
}

$text = get_input('raw_json');
$file = get_uploaded_file('json_file');

if (empty($text) || empty($file)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

