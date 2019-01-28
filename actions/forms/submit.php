<?php

use ColdTrick\Forms\Result;

$form_guid = (int) get_input('form_guid');
if (empty($form_guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$form = get_entity($form_guid);
if (!$form instanceof \Form) {
	return elgg_error_response(elgg_echo('save:fail'));
}

// make sticky form
elgg_make_sticky_form("forms_{$form_guid}");

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

// log submission
$form->logSubmission();

// on success: forward to thank you page
elgg_clear_sticky_form("forms_{$form_guid}");

return elgg_ok_response('', '', elgg_generate_entity_url($form, 'thankyou'));
