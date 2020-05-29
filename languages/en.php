<?php

return [
	
	'item:object:form' => "Form",
	'collection:object:form' => "Forms",
	
	'forms:invalid_input_exception:required' => "Missing required field",
	'forms:invalid_input_exception:pattern' => "Field input doesn't match pattern",
	'forms:invalid_input_exception:value:email' => "Please provide a valid e-mail address",
	'forms:invalid_input_exception:value:number' => "Please provide a valid number",
	
	'forms:entity_menu:compose' => "Compose",
	'forms:entity_menu:copy' => "Copy",
	'forms:entity_menu:copy:confirm' => "Are you sure you wish to copy this form?",
	
	'forms:page_menu:all' => "All forms",
	'forms:page_menu:validation_rules' => "Manage validation rules",
	
	'forms:add' => "Create form",
	
	'forms:add:title' => "Create form",
	'forms:edit:title' => "Edit form: %s",
	'forms:compose:title' => "Compose form: %s",
	'forms:thankyou:title' => "Thank you for completing: %s",
	'forms:by_line:submissions' => "Number of form submissions",
	
	'forms:submit:tab:text' => "Page %d",
	
	'forms:compose:section:new' => "New Section",
	'forms:compose:section:add' => "Add section",
	'forms:compose:page:new' => "New page",
	'forms:compose:page:add' => "Add page",

	'forms:compose:edit:title' => "Edit title",
	
	'forms:compose:conditional_section:value:label' => "The following fields should show when value matches",
	'forms:compose:conditional_section:placeholder' => "Drop your conditional fields here",
	'forms:compose:conditional_section:invalid_drop' => "You can't move this field here as it contains conditional sections.",

	'forms:compose:fields:title' => "New Fields",

	'forms:compose:field:conditional:title' => "Add conditional section",
	
	'forms:compose:field:type:text' => "Text",
	'forms:compose:field:type:email' => "E-mail address",
	'forms:compose:field:type:number' => "Number",
	'forms:compose:field:type:plaintext' => "Plaintext",
	'forms:compose:field:type:longtext' => "Longtext",
	'forms:compose:field:type:radio' => "Radio",
	'forms:compose:field:type:file' => "File",
	'forms:compose:field:type:date' => "Date",
	'forms:compose:field:type:select' => "Select",
	'forms:compose:field:type:checkbox' => "Checkbox",
	'forms:compose:field:type:checkboxes' => "Checkboxes",
	'forms:compose:field:type:hidden' => "Hidden",
	
	'forms:compose:field:edit:label' => "Label",
	'forms:compose:field:edit:type' => "Type",
	'forms:compose:field:edit:help' => "Help text",
	'forms:compose:field:edit:required' => "Required",
	'forms:compose:field:edit:options' => "Options",
	'forms:compose:field:edit:options:help' => "Comma separate the options for this field",
	'forms:compose:field:edit:validation_rule' => "Validation rule",
	'forms:compose:field:edit:value' => "Value",
	'forms:compose:field:edit:default_value' => "Default value",
	'forms:compose:field:edit:default_value:help' => "You can select a user attribute to be prefilled in the field",
	'forms:compose:field:edit:default_value:none' => "No default value",
	
	'forms:compose:field:edit:email_recipient' => "Add e-mail address to:",
	'forms:compose:field:edit:email_recipient:none' => "No recipient",
	'forms:compose:field:edit:email_recipient:to' => "To",
	'forms:compose:field:edit:email_recipient:cc' => "CC",
	'forms:compose:field:edit:email_recipient:bcc' => "BCC",
	
	'forms:compose:field:edit:select:multiple' => "Allow multiple values to be selected",
	'forms:view:field:select:empty' => "Select a value...",
	
	'forms:edit:friendly_url' => "URL to the form",
	'forms:edit:definition' => "Definition file (from export)",
	'forms:edit:thankyou' => "Thank you message",
	'forms:edit:thankyou:help' => "When a user completes the form they will be send to a thank you page, you can add custom text to that page.",
	
	'forms:edit:endpoint' => "Endpoint",
	'forms:edit:endpoint:help' => "When a form is filled in what should happen with the results",
	'forms:edit:endpoint:email' => "E-mail notification",
	'forms:edit:endpoint:csv' => "CSV file",
	
	'forms:endpoint:csv' => "CSV file configuration",
	'forms:endpoint:csv:description' => "Form results will be stored in a CSV which can be downloaded at any time.
An e-mail address can be configured to receive a notification when a form is submitted. Also it's possible to configure users who are allowed to download the CSV file.
Due to the CSV storage File questions on the form aren't allowed.",
	'forms:endpoint:csv:to' => "E-mail address for notification upon form completion",
	'forms:endpoint:csv:to:help' => "An e-mail notification can be send when a form is filled in. This notification does not contain the form answers.",
	'forms:endpoint:csv:downloaders' => "Who can download the CSV file with the results",
	'forms:endpoint:csv:downloaders:help' => "By default only site administrators can download the results of this form. Additional users can be configured",
	
	'forms:endpoint:csv:notification:subject' => "New submittion of the form: %s",
	'forms:endpoint:csv:notification:body' => "Hi,

A new submittion was made on the form: %s

If you wish to download all responses until now check the link below

%s",
	
	'forms:endpoint:csv:download' => "Download CSV results",
	'forms:endpoint:csv:clear' => "Clear CSV results",
	'forms:endpoint:csv:clear:confirm' => "Are you sure you wish to clear all results?",
	
	'form:endpoint:csv:validation:file_exists' => "The form already has some responses. Changing the form could result in unusable output in the CSV file.
To make sure headers in the CSV match with the data, please download the results first and then clear the results.",
	
	'forms:endpoint:email' => "Email configuration",
	'forms:endpoint:email:description' => "Form results will be e-mailed to the configured e-mail addresses. It's also possible to e-mail a copy of the form to the user who filled in the form.
Certain fields on the form can be configured to also receive the complete form as an e-mail notfication.",
	'forms:endpoint:email:subject' => "A new reponse for form: %s",
	'forms:endpoint:email:to' => "To",
	'forms:endpoint:email:cc' => "CC",
	'forms:endpoint:email:cc:user' => "Automatically send a copy to the user filling in this form",
	'forms:endpoint:email:bcc' => "BCC",
	'forms:endpoint:email:body:attachment' => "(see attachment)",
	
	'forms:definition:validation:error:imprint' => "This form can't be used",
	'forms:definition:validation:error:csv:file' => "The field with the label '%s' is of the type File, this isn't allowed for CSV forms.",
	
	'forms:view:error:validation' => "The form '%s' contains error which prevent it's use. Please contact the form owner to fix this issue and try again later.",
	
	'forms:entity:clone:title' => "Copy of %s",
	
	'forms:sidebar:history:title' => "History",
	'forms:sidebar:history:create' => "created",
	'forms:sidebar:history:update' => "updated",
	'forms:sidebar:history:delete' => "deleted",
	
	'forms:validation_rules:title' => "Validation rules",
	'forms:validation_rules:none' => "No validation rules configured yet",
	
	'forms:validation_rule:label' => "Label",
	'forms:validation_rule:label:help' => "This is to easily select the validation rule",
	'forms:validation_rule:error_message' => "Custom error message",
	'forms:validation_rule:error_message:help' => "When the field doesn't match the pattern show this error message instead of the default browser message",
	'forms:validation_rule:regex' => "Regex",
	'forms:validation_rule:regex:help' => "A validation rule consists of a regex to match to the input (using the HTML5 pattern structure)",
	'forms:validation_rule:regex:output' => "Regex: %s",
	'forms:validation_rule:input:none' => "No validation",
	
	'forms:import:title' => "Import a form definition",
	'forms:import:or' => "or",
	'forms:import:warning:definition' => "This form already has a defninition. If you import a new definition that will overrule the current definition.",
	'forms:import:json_text' => "JSON text (from export file)",
	'forms:import:json_text:help' => "You can paste the contents of an export file here",
	'forms:import:json_file' => "JSON file (the export file)",
	
	'forms:thankyou:generic' => "You've successfully completed %s. Thank you.",
	'forms:thankyou:again' => "Start the form again",
	
	'forms:result:validate:error' => "%s: %s",
	
	'forms:action:edit:error:friendly_url' => "The friendly URL is not valid or already in use, please change it",
	
	'forms:action:validation_rules:edit:error:regex' => "The regex you provided doesn't seem to be valid",
	
	'forms:action:definition:error:no_def' => "No form definition could be found",
	'forms:action:definition:import:error:json_format' => "The format of the import is incorrect",
	'forms:action:definition:import:error:json_definition' => "An error occured while importing the definition",
	'forms:action:definition:import:success' => "Definition successfully imported",
	
	'forms:action:endpoints:csv:clear:error:endpoint' => "This form doesn't have a CSV enpoint configured",
	'forms:action:endpoints:csv:clear:error:no_file' => "There is no CSV file to remove",
	'forms:action:endpoints:csv:clear:error:delete_file' => "An error occured while removing the CSV file",
	'forms:action:endpoints:csv:clear:success' => "The CSV file was removed successfully",
];
