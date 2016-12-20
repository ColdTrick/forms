<?php

require_once(dirname(__FILE__) . '/lib/functions.php');

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
	
	// register actions
	elgg_register_action('forms/edit', dirname(__FILE__) . '/actions/forms/edit.php', 'admin');
	elgg_register_action('forms/delete', dirname(__FILE__) . '/actions/forms/delete.php', 'admin');
}
