<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggEntity)) {
	return;
}

$by_line = [];

$by_line[] = elgg_view_friendly_time($entity->time_created);

$container_entity = $entity->getContainerEntity();
if ($container_entity instanceof ElggGroup && ($container_entity->getGUID() !== elgg_get_page_owner_guid())) {
	$group_link = elgg_view('output/url', [
		'href' => $container_entity->getURL(),
		'text' => $container_entity->name,
		'is_trusted' => true,
	]);
	$by_line[] = elgg_echo("byline:ingroup", [$group_link]);
}

if (!empty($by_line)) {
	echo implode(' ', $by_line);
}