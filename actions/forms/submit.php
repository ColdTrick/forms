<?php

use ColdTrick\Forms\Result;

$form_guid = (int) get_input('form_guid');
elgg_entity_gatekeeper($form_guid, 'object', \Form::SUBTYPE);

// make sticky form
elgg_make_sticky_form("forms_{$form_guid}");

/* @var $form \Form */
$form = get_entity($form_guid);

// create a result
$result = new Result($form);

// validate input
if (!$result->validate()) {
	return elgg_error_response();
}

// process the result
$endpoint = $form->getEndpoint();
if (!empty($endpoint)) {
	$endpoint->process($result);
}

// on success: forward to thank you page
elgg_clear_sticky_form("forms_{$form_guid}");
