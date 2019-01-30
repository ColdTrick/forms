<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof Form || !elgg_is_active_plugin('system_log')) {
	return;
}

$system_log = system_log_get_log([
	'object_class' => Form::class,
	'object_type' => 'object',
	'object_subtype' => Form::SUBTYPE,
	'limit' => 10,
	'object_id' => $entity->guid,
]);
if (empty($system_log)) {
	return;
}

$result = [];
/* @var $entry \Elgg\SystemLog\SystemLogEntry */
foreach ($system_log as $entry) {
	$line = [];
	$performer = get_entity($entry->performed_by_guid);
	if ($performer instanceof ElggUser) {
		$line[] = elgg_view('output/url', [
			'text' => $performer->getDisplayName(),
			'href' => $performer->getURL(),
			'is_trusted' => true,
		]);
	} else {
		$line[] = elgg_echo('unknown');
	}
	
	switch ($entry->event) {
		case 'create':
		case 'update':
		case 'delete':
			$line[] = elgg_echo("forms:sidebar:history:{$entry->event}");
			
			break;
		default:
			if (stristr($entry->event, ':before') || stristr($entry->event, ':after')) {
				continue(2);
			}
			
			$line[] = $entry->event;
			
			break;
	}
	
	$line[] = elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_get_friendly_time($entry->time_created));
	
	$result[(int) $entry->id] = elgg_format_element('li', ['class' => 'elgg-item'], implode(' ', $line));
}

if (empty($result)) {
	// all log entries were filtered away
	return;
}

krsort($result);

$content = elgg_format_element('ul', ['class' => 'elgg-list'], implode(PHP_EOL, $result));

echo elgg_view_module('aside', elgg_echo('forms:sidebar:history:title'), $content);
