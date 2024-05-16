<?php

use Elgg\Exceptions\HttpException;

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', \Form::SUBTYPE);

/* @var $entity Form */
$entity = get_entity($guid);
if (!$entity->isValid()) {
	throw new HttpException(elgg_echo('forms:view:error:validation', [$entity->getDisplayName()]), ELGG_HTTP_NOT_FOUND);
}

$sticky_values = elgg_get_sticky_values("forms_{$guid}");

// build page elements
$title = $entity->getDisplayName();

$body = elgg_view('output/longtext', [
	'value' => $entity->description,
	'class' => 'mbm',
]);

$body .= elgg_view_form('forms/submit', [], [
	'entity' => $entity,
	'sticky_values' => $sticky_values,
]);

// clear sticky values
elgg_clear_sticky_form("forms_{$guid}");

// draw page
echo elgg_view_page($title, [
	'content' => $body,
	'entity' => $entity,
	'filter' => false,
]);
