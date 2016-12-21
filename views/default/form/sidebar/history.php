<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Form)) {
	return;
}

$system_log = get_system_log(false, '', Form::class, 'object', Form::SUBTYPE, 10, 0, false, 0, 0, $entity->getGUID());
if (empty($system_log)) {
	return;
}

$result = [];
foreach ($system_log as $entry) {
	$line = [];
	$performer = get_entity($entry->performed_by_guid);
	if ($performer instanceof ElggUser) {
		$line[] = elgg_view('output/url', [
			'text' => $performer->name,
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
	
	$line[] = elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_get_friendly_time($entry->time_created));
	
	$result[(int) $entry->id] = elgg_format_element('li', ['class' => 'elgg-item'], implode(' ', $line));
}

if (empty($result)) {
	// all log entries were filtered away
	return;
}

krsort($result);

$content = elgg_format_element('ul', ['class' => 'elgg-list'], implode('', $result));

echo elgg_view_module('aside', elgg_echo('forms:sidebar:history:title'), $content);
