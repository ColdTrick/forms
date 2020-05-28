<?php

use ColdTrick\Forms\Bootstrap;
use Elgg\Router\Middleware\AdminGatekeeper;

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
		'add:object:form' => [
			'path' => '/forms/add/{guid}',
			'resource' => 'forms/add',
			'middleware' => [
				AdminGatekeeper::class,
			],
		],
		'edit:object:form' => [
			'path' => '/forms/edit/{guid}',
			'resource' => 'forms/edit',
			'middleware' => [
				AdminGatekeeper::class,
			],
		],
		'compose:object:form' => [
			'path' => '/forms/compose/{guid}',
			'resource' => 'forms/compose',
			'middleware' => [
				AdminGatekeeper::class,
			],
		],
		'view:object:form' => [
			'path' => '/forms/view/{guid}',
			'resource' => 'forms/view',
		],
		'thankyou:object:form' => [
			'path' => '/forms/thankyou/{guid}',
			'resource' => 'forms/thankyou',
		],
		'collection:object:form:all' => [
			'path' => '/forms/all',
			'resource' => 'forms/all',
		],
		'collection:validation_rules' => [
			'path' => '/forms/validation_rules',
			'resource' => 'forms/validation_rules',
			'middleware' => [
				AdminGatekeeper::class,
			],
		],
		'default:object:form' => [
			'path' => '/forms',
			'resource' => 'forms/all',
		],
		'view:object:form:friendly' => [
			'path' => '/forms/{title}',
			'controller' => \ColdTrick\Forms\Controllers\FriendlyForm::class,
		],
	],
	'actions' => [
		'forms/compose' => ['access' => 'admin'],
		'forms/copy' => ['access' => 'admin'],
		'forms/definition/export' => ['access' => 'admin'],
		'forms/definition/import' => ['access' => 'admin'],
		'forms/edit' => ['access' => 'admin'],
		'forms/endpoints/csv/clear' => ['access' => 'admin'],
		'forms/submit' => ['access' => 'public'],
		'forms/validation_rules/delete' => ['access' => 'admin'],
		'forms/validation_rules/edit' => ['access' => 'admin'],
	],
	'hooks' => [
		'access:collections:write' => [
			'user' => [
				'\ColdTrick\Forms\Access::formWriteAccess' => [],
			],
		],
		'register' => [
			'menu:entity' => [
				'\ColdTrick\Forms\Menus\Entity::registerForm' => [],
				'\ColdTrick\Forms\Menus\Entity::addCsvDownload' => [],
				'\ColdTrick\Forms\Menus\Entity::addCsvClear' => [],
			],
			'menu:page' => [
				'\ColdTrick\Forms\Menus\Page::registerValidationRules' => [],
			],
			'menu:title' => [
				'\ColdTrick\Forms\Menus\Title::addCsvDownload' => [],
				'\ColdTrick\Forms\Menus\Title::addCsvClear' => [],
			],
			'menu:validation_rule' => [
				'\ColdTrick\Forms\Menus\ValidationRule::registerEdit' => [],
			],
		],
	],
	'view_extensions' => [
		'css/elgg' => [
			'css/forms.css' => [],
		],
	],
];
		