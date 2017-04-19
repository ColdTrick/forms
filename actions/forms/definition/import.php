<?php

use \Symfony\Component\HttpFoundation\File\UploadedFile;

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!($entity instanceof Form) || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('noaccess'));
}

$text = get_input('json_text');
$files = elgg_get_uploaded_files('json_file');
$file = elgg_extract(0, $files);
if (empty($text) && empty($file)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$json = $text;
if (($file instanceof UploadedFile) && $file->isValid()) {
	// file import wins over raw text
	$json = file_get_contents($file->getPathname());
}

$data = @json_decode($json, true);
if (empty($data)) {
	return elgg_error_response(elgg_echo('forms:action:definition:import:error:json_format'));
}

if (!$entity->importDefinition($json)) {
	return elgg_error_response(elgg_echo('forms:action:definition:import:error:json_definition'));
}

return elgg_ok_response('', elgg_echo('forms:action:definition:import:success'), REFERER);
