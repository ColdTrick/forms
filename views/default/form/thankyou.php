<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Form)) {
	return;
}

if (!empty($entity->thankyou)) {
	echo elgg_view('output/longtext', [
		'value' => $entity->thankyou,
		'class' => 'mbl',
	]);
} else {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('forms:thankyou:generic', [$entity->getDisplayName()]),
		'class' => 'mbl',
	]);
}

echo elgg_format_element('div', [], elgg_view('output/url', [
	'text' => elgg_echo('forms:thankyou:again'),
	'href' => $entity->getURL(),
	'class' => 'elgg-button elgg-button-action',
]));
