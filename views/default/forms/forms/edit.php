<?php
/**
 * Create/edit a form
 *
 * @uses $vars['entity'] (optional) the form to edit
 * @uses $vars['container_guid'] where to place the form
 */

/* @var $entity \Form */
$entity = elgg_extract('entity', $vars);

elgg_import_esm('forms/forms/edit');

echo elgg_view('entity/edit/header', [
	'entity' => $entity,
	'entity_type' => 'object',
	'entity_subtype' => \Form::SUBTYPE,
]);

$fields = elgg()->fields->get('object', \Form::SUBTYPE);
foreach ($fields as $field) {
	$name = elgg_extract('name', $field);

	switch (elgg_extract('#type', $field)) {
		case 'access':
			$field['container_guid'] = elgg_extract('container_guid', $vars);
			if ($entity instanceof \Form) {
				$field['entity'] = $entity;
			}

		// fall through to set value
		default:
			$field['value'] = elgg_extract($name, $vars);
			break;
	}

	echo elgg_view_field($field);
}

if (!$entity instanceof \Form) {
	echo elgg_view_field([
		'#type' => 'file',
		'#label' => elgg_echo('forms:edit:definition'),
		'name' => 'definition',
	]);
}

echo elgg_view('form/edit/endpoint', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity?->guid,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
