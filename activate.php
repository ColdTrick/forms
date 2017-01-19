<?php
/**
 * Called when the plugin is activated
 */

if (get_subtype_id('object', Form::SUBTYPE)) {
	update_subtype('object', Form::SUBTYPE, Form::class);
} else {
	add_subtype('object', Form::SUBTYPE, Form::class);
}
