<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Form)) {
	return;
}

$by_line = [];

// time created
$by_line[] = elgg_view_friendly_time($entity->time_created);

// container
$container_entity = $entity->getContainerEntity();
if ($container_entity instanceof ElggGroup && ($container_entity->getGUID() !== elgg_get_page_owner_guid())) {
	$group_link = elgg_view('output/url', [
		'href' => $container_entity->getURL(),
		'text' => $container_entity->name,
		'is_trusted' => true,
	]);
	$by_line[] = elgg_echo("byline:ingroup", [$group_link]);
}

// number of submissions
if ($entity->canEdit()) {
	$count = (int) $entity->submitted_count;
	$icon = elgg_view_icon('check-square-o');
	$by_line[] = elgg_format_element('span', [
		'title' => elgg_echo('forms:by_line:submissions'),
		'class' => 'forms-submissions',
	], "{$icon} {$count}");
}

if (!empty($by_line)) {
	echo implode(' ', $by_line);
}
