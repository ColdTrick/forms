<?php

return [
	
	'item:object:form' => "Form",
	
	'forms:entity_menu:compose' => "Compose",
	'forms:entity_menu:copy' => "Copy",
	'forms:entity_menu:copy:confirm' => "Are you sure you wish to copy this form?",
	
	'forms:page_menu:validation_rules' => "Manage validation rules",
	
	'forms:add' => "Create form",
	'forms:all:title' => "All forms",
	'forms:add:title' => "Create form",
	'forms:edit:title' => "Edit form: %s",
	'forms:compose:title' => "Compose form: %s",
	
	'forms:compose:section:new' => "New Section",
	'forms:compose:section:add' => "Add section",
	'forms:compose:page:new' => "New page",
	'forms:compose:page:add' => "Add page",
	
	'forms:compose:conditional_section:value:label' => "The following fields should show when value matches",
	'forms:compose:conditional_section:placeholder' => "Drop your conditional fields here",
	'forms:compose:conditional_section:invalid_drop' => "You can't move this field here as it contains conditional sections.",

	'forms:compose:fields:title' => "New Fields",

	'forms:compose:field:conditional:title' => "Add conditional section",
	
	'forms:compose:field:type:text' => "Text",
	'forms:compose:field:type:plaintext' => "Plaintext",
	'forms:compose:field:type:longtext' => "Longtext",
	'forms:compose:field:type:radio' => "Radio",
	'forms:compose:field:type:file' => "File",
	'forms:compose:field:type:date' => "Date",
	'forms:compose:field:type:select' => "Select",
	'forms:compose:field:type:checkbox' => "Checkbox",
	'forms:compose:field:type:checkboxes' => "Checkboxes",
	
	'forms:compose:field:edit:label' => "Label",
	'forms:compose:field:edit:type' => "Type",
	'forms:compose:field:edit:help' => "Help text",
	'forms:compose:field:edit:required' => "Required",
	'forms:compose:field:edit:options' => "Options",
	'forms:compose:field:edit:options:help' => "Comma separate the options for this field",
	'forms:compose:field:edit:validation_rule' => "Validation rule",
	
	'forms:view:field:select:empty' => "Select a value...",
	
	'forms:edit:friendly_url' => "URL to the form",
	
	'forms:endpoint:email' => "Email configuration",
	'forms:endpoint:email:subject' => "A new reponse for form: %s",
	'forms:endpoint:email:to' => "To",
	'forms:endpoint:email:cc' => "CC",
	'forms:endpoint:email:bcc' => "BCC",
	
	'forms:entity:clone:title' => "Copy of %s",
	
	'forms:sidebar:history:title' => "History",
	'forms:sidebar:history:create' => "created",
	'forms:sidebar:history:update' => "updated",
	'forms:sidebar:history:delete' => "deleted",
	
	'forms:validation_rules:title' => "Validation rules",
	'forms:validation_rules:none' => "No validation rules configured yet",
	
	'forms:validation_rule:label' => "Label",
	'forms:validation_rule:label:help' => "This is to easily select the validation rule",
	'forms:validation_rule:regex' => "Regex",
	'forms:validation_rule:regex:help' => "A validation rule consists of a regex to match to the input (using the HTML5 pattern structure)",
	'forms:validation_rule:regex:output' => "Regex: %s",
	'forms:validation_rule:input:none' => "No validation",
	
	'forms:action:edit:error:friendly_url' => "The friendly URL is not valid or already in use, please change it",
	
	'forms:action:validation_rules:edit:error:regex' => "The regex you provided doesn't seem to be valid",
];
