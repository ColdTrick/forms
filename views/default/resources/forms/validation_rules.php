<?php

elgg_push_collection_breadcrumbs('object', \Form::SUBTYPE);

elgg_register_menu_item('title', [
	'name' => 'add',
	'text' => elgg_echo('add'),
	'icon' => 'plus',
	'href' => 'ajax/form/forms/validation_rules/edit',
	'link_class' => [
		'elgg-button',
		'elgg-button-action',
		'elgg-lightbox',
	],
]);

echo elgg_view_page(elgg_echo('forms:validation_rules:title'), [
	'content' => elgg_view('form/validation_rules/list', ['rules' => forms_get_validation_rules()]),
	'filter_id' => 'forms/validation_rules',
]);
