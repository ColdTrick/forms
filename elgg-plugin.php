<?php

use ColdTrick\Forms\Bootstrap;

require_once(dirname(__FILE__) . '/lib/functions.php');

return [
	'bootstrap' => Bootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'form',
			'class' => \Form::class,
		],
	],
	'routes' => [
	],
	'actions' => [
		'forms/compose' => ['access' => 'admin'],
		'forms/copy' => ['access' => 'admin'],
		'forms/definition/export' => ['access' => 'admin'],
		'forms/definition/import' => ['access' => 'admin'],
		'forms/delete' => ['access' => 'admin'],
		'forms/edit' => ['access' => 'admin'],
		'forms/submit' => ['access' => 'public'],
		'forms/validation_rules/delete' => ['access' => 'admin'],
		'forms/validation_rules/edit' => ['access' => 'admin'],
	],
];
		