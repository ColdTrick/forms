<?php

$friendly_url = elgg_get_friendly_title(get_input('title'));
$friendly_url = forms_generate_valid_friendly_url($friendly_url);

echo json_encode([
	'friendly_url' => $friendly_url,
]);
