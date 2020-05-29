<?php

// get saved values
$endpoint_config = elgg_extract('endpoint_config', $vars, []);
$config = elgg_extract('email', $endpoint_config, []);

// make form elements
$email = '';

$email .= elgg_view('output/longtext', [
	'value' => elgg_echo('forms:endpoint:email:description'),
]);

$email .= elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('forms:endpoint:email:to'),
	'name' => 'endpoint_config[email][to]',
	'value' => elgg_extract('to', $config),
	'required' => true,
]);
$email .= elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('forms:endpoint:email:cc'),
	'name' => 'endpoint_config[email][cc]',
	'value' => elgg_extract('cc', $config),
]);
$email .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('forms:endpoint:email:cc:user'),
	'name' => 'endpoint_config[email][cc_user]',
	'checked' => (bool) elgg_extract('cc_user', $config),
]);
$email .= elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('forms:endpoint:email:bcc'),
	'name' => 'endpoint_config[email][bcc]',
	'value' => elgg_extract('bcc', $config),
]);

// prepare output
echo elgg_view_module('info', elgg_echo('forms:endpoint:email'), $email);
