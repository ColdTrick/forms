<?php

use ColdTrick\Forms\Definition\ConditionalSection;

$conditional_section = elgg_extract('conditional_section', $vars);
if (!$conditional_section instanceof ConditionalSection) {
	return;
}

echo elgg_view('form/compose/edit_conditional_section', [
	'value' => $conditional_section->getValue(),
	'fields' => $conditional_section->getFields(),
]);
