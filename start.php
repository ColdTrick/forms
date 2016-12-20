<?php

// register default elgg events
elgg_register_event_handler('init', 'system', 'forms_init');
elgg_register_plugin_hook_handler('route:rewrite', 'forms', '\ColdTrick\Forms\PageHandler::routeRewrite');

/**
 * Init function for this plugin
 *
 * @return void
 */
function forms_init() {
	
	// register page handler
	elgg_register_page_handler('forms', '\ColdTrick\Forms\PageHandler::forms');
}
