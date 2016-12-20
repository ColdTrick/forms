<?php

echo json_encode([
	'friendly_title' => elgg_get_friendly_title(get_input('title')),
]);
