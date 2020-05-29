<?php

// get saved values
$endpoint_config = elgg_extract('endpoint_config', $vars, []);
$config = elgg_extract('csv', $endpoint_config, []);

// make form elements
$csv = '';

$csv .= elgg_view('output/longtext', [
	'value' => elgg_echo('forms:endpoint:csv:description'),
]);

$csv .= elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('forms:endpoint:csv:to'),
	'#help' => elgg_echo('forms:endpoint:csv:to:help'),
	'name' => 'endpoint_config[csv][to]',
	'value' => elgg_extract('to', $config),
]);

$csv .= elgg_view_field([
	'#type' => 'userpicker',
	'#label' => elgg_echo('forms:endpoint:csv:downloaders'),
	'#help' => elgg_echo('forms:endpoint:csv:downloaders:help'),
	'name' => 'endpoint_config[csv][downloaders]',
	'value' => elgg_extract('downloaders', $config),
]);

// prepare output
echo elgg_view_module('info', elgg_echo('forms:endpoint:csv'), $csv);
