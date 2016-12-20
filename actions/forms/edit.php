<?php

elgg_make_sticky_form('forms/edit');

$guid = (int) get_input('guid');
$container_guid = (int) get_input('container_guid');

$title = get_input('title');
$friendly_url = get_input('friendly_url', elgg_get_friendly_title($title));
$description = get_input('description');
$access_id = (int) get_input('access_id');

$entity = false;
if (!empty($guid)) {
	$entity = get_entity($guid);
	if (!($entity instanceof Form) || !$entity->canEdit()) {
		elgg_error_response(elgg_echo('noaccess'));
	}
}

if (empty($title) || empty($friendly_url) || empty($container_guid)) {
	elgg_error_response(elgg_echo('error:missing_data'));
}

$container = get_entity($container_guid);
if (!($container instanceof ElggGroup)) {
	$container = elgg_get_site_entity();
}

if (empty($entity)) {
	
	if (!$container->canWriteToContainer(0, 'object', Form::SUBTYPE)) {
		elgg_error_response(elgg_echo('actionunauthorized'));
	}
	
	$entity = new Form();
	$entity->container_guid = $container->getGUID();
	
	if (!$entity->save()) {
		elgg_error_response(elgg_echo('save:fail'));
	}
}

$entity->title = $title;
$entity->friendly_url = $friendly_url;
$entity->description = $description;
$entity->access_id = $access_id;

if (!$entity->save()) {
	elgg_error_response(elgg_echo('save:fail'));
}

elgg_clear_sticky_form('forms/edit');

elgg_ok_response('', elgg_echo('save:success'), 'forms/all');
