<?php

elgg_require_js('js/forms/compose');

$content = elgg_view('form/compose', $vars);

$body = elgg_view_layout('one_column', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
