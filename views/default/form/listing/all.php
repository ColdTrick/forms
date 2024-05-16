<?php
/**
 * List all Forms
 *
 * @uses $vars['options'] additional options
 */

$defaults = [
	'type' => 'object',
	'subtype' => \Form::SUBTYPE,
	'full_view' => false,
	'no_results' => elgg_echo('forms:no_results'),
	'distinct' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

echo elgg_list_entities($options);
