<?php

use ColdTrick\Forms\Result;

$form_guid = (int) get_input('form_guid');
elgg_entity_gatekeeper($form_guid, 'object', \Form::SUBTYPE);

/* @var $form \Form */
$form = get_entity($form_guid);

// validate input

// create a result
$result = new Result($form);

// process the result
$endpoint = $form->getEndpoint();
if (!empty($endpoint)) {
	$endpoint->process($result);
}

// on success: forward to thank you page
