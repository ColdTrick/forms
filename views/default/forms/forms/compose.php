<?php

elgg_require_js('forms/compose');

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

echo '<div class="clearfix">';

echo '<div class="elgg-col elgg-col-3of4">';
echo elgg_view('form/compose/canvas', $vars);
echo '</div>';

echo '<div class="elgg-col elgg-col-1of4">';
echo elgg_view('form/compose/fields', $vars);
echo '</div>';

echo '</div>';

echo elgg_view('form/compose/edit_field', $vars);

$footer = elgg_view_field([
	'#type' => 'submit',
	'class' => 'forms-compose-save',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
