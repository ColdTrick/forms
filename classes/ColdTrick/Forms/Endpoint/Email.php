<?php

namespace ColdTrick\Forms\Endpoint;

use ColdTrick\Forms\Endpoint;
use ColdTrick\Forms\Result;
use Zend\Mail\Address;
use ColdTrick\Forms\Definition\Field;

class Email extends Endpoint {
	
	/**
	 * @var Result the form result
	 */
	protected $result;
	
	/**
	 * @var array uploaded files need to be added as attachment in the email
	 */
	protected $attachments;
	
	/**
	 * @var array the e-mail recipients
	 */
	protected $recipients = [
		'to' => [],
		'cc' => [],
		'bcc' => [],
	];
	
	/**
	 * {@inheritDoc}
	 * @see \ColdTrick\Forms\Endpoint::__construct()
	 */
	public function __construct(array $configuration) {
		
		parent::__construct($configuration);
		
		$this->addRecipient('to', elgg_extract('to', $configuration));
		$this->addRecipient('cc', elgg_extract('cc', $configuration));
		$this->addRecipient('bcc', elgg_extract('bcc', $configuration));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \ColdTrick\Forms\Endpoint::process()
	 */
	public function process(Result $result) {
		$this->result = $result;
		
		$form = $this->result->getForm();
		
		$subject = elgg_echo('forms:endpoint:email:subject', [$form->getDisplayName()]);
		$body = $this->getBody();
		
		// set recipients after body processing because some can be added
		$to = $this->getRecipients('to');
		$from = $this->getFrom();
		
		// get additional e-mail params
		$params = $this->getParams();
				
		return elgg_send_email($from, $to, $subject, $body, $params);
	}
	
	/**
	 * Build the email body
	 *
	 * @return string
	 */
	protected function getBody() {
		$body = '';
		
		foreach ($this->result->getPages() as $page) {
			$section_content = [];
			
			foreach ($page->getSections() as $section) {
				$field_content = [];
				
				foreach ($section->getFields() as $field) {
					
					$this->addRecipientFromField($field);
					$field_content[] = $this->getBodyField($field);
					
					// add the conditional sections based on the value of the field
					foreach ($field->getConditionalSections(true) as $conditional_section) {
						
						foreach ($conditional_section->getFields() as $conditional_field) {
							
							$this->addRecipientFromField($conditional_field);
							$field_content[] = $this->getBodyField($conditional_field);
						}
					}
				}
				
				if (empty($field_content)) {
					// no fields in this section
					continue;
				}
				
				$section_content[] = elgg_format_element('h4', [], $section->getTitle());
				$section_content[] = PHP_EOL;
				$section_content[] = elgg_format_element('table', [], implode(PHP_EOL, $field_content));
			}
			
			if (empty($section_content)) {
				// no sections on this page
				continue;
			}
			if (!empty($body)) {
				$body .= elgg_format_element('h3', [], '<hr />');
				$body .= PHP_EOL;
			}
			$body .= implode(PHP_EOL, $section_content);
		}
		
		return $body;
	}
	
	/**
	 * Format a field for in the mail message
	 *
	 * @param Field $field
	 *
	 * @return void|string
	 */
	protected function getBodyField(Field $field) {
		
		$row = elgg_format_element('td', [], $field->getLabel() . ': ');
		
		$value = $field->getValue();
		if ($field->getType() === 'file') {
			$this->addAttachment($field);
			
			$value = [];
			$uploaded_files = $field->getUploadedFiles(true);
			if (!empty($uploaded_files)) {
				foreach ($uploaded_files as $uploaded_file) {
					$value[] = $uploaded_file->getClientOriginalName();
				}
				
				$value[] = elgg_echo('forms:endpoint:email:body:attachment');
			}
		}
		
		if (is_array($value)) {
			$value = implode(', ', $value);
		}
		$row .= elgg_format_element('td', [], $value);
		$row .= PHP_EOL;
		
		return elgg_format_element('tr', [], $row);
	}
	
	/**
	 * Get the from email address
	 *
	 * @return string
	 */
	protected function getFrom() {
		
		$site = elgg_get_site_entity();
		$email = $site->email;
		if (empty($email)) {
			$email = "noreply@{$site->getDomain()}";
		}
		
		$address = new Address($email, $site->getDisplayName());
		
		return $address->toString();
	}
	
	/**
	 * Get additional email params
	 *
	 * @return array
	 */
	protected function getParams() {
		$result = [
			'cc' => $this->getRecipients('cc'),
			'bcc' => $this->getRecipients('bcc'),
		];
		
		if (!empty($this->attachments)) {
			$result['attachments'] = $this->attachments;
		}
		
		return $result;
	}
	
	/**
	 * Add a file field as an attachment
	 *
	 * @param Field $field the file field
	 *
	 * @return void
	 */
	protected function addAttachment(Field $field) {
		
		if ($field->getType() !== 'file') {
			return;
		}
		
		if (!isset($this->attachments)) {
			$this->attachments = [];
		}
		
		$uploaded_files = $field->getUploadedFiles(true);
		if (empty($uploaded_files)) {
			return;
		}
		
		foreach ($uploaded_files as $uploaded_file) {
			$attachment = [
				'filename' => $uploaded_file->getClientOriginalName(),
				'mimetype' => $uploaded_file->getMimeType(),
				'filepath' => $uploaded_file->getPathname(),
			];
			
			$this->attachments[] = $attachment;
		}
	}
	
	/**
	 * Add a recipient to the e-mail
	 *
	 * @param string $type    to, cc or bcc
	 * @param string $address the e-mail address
	 *
	 * @return void
	 */
	protected function addRecipient($type, $address) {
		
		if (!in_array($type, ['to', 'cc', 'bcc'])) {
			return;
		}
		
		if (!is_email_address($address)) {
			return;
		}
		
		if ($type === 'to' && (count($this->recipients['to']) >= 1)) {
			// multiple to can currently only be handled by html_email_handler
			if (!elgg_is_active_plugin('html_email_handler') || (elgg_get_plugin_setting('notifications', 'html_email_handler') !== 'yes')) {
				return;
			}
		}
		
		$this->recipients[$type][] = $address;
	}
	
	/**
	 * Add an email field to the configured recipient
	 *
	 * @param Field $field the field to check
	 *
	 * @return void
	 */
	protected function addRecipientFromField(Field $field) {
		
		if (!$field->getValue()) {
			return;
		}
		
		switch ($field->getType()) {
			case 'email':
			case 'hidden':
				// these fields support e-mail address options
				break;
			default:
				return;
				break;
		}
		
		$field_config = $field->getConfig();
		$type = elgg_extract('email_recipient', $field_config);
		if (empty($type)) {
			// not configured as additional recipient
			return;
		}
		
		$this->addRecipient($type, $field->getValue());
	}
	
	/**
	 * Get the recipients for a type
	 *
	 * @param string $type to, cc or bcc
	 *
	 * @return string|string[]
	 */
	protected function getRecipients($type) {
		
		if (!in_array($type, ['to', 'cc', 'bcc'])) {
			return '';
		}
		
		$recipients = elgg_extract($type, $this->recipients, []);
		if (empty($recipients)) {
			return '';
		}
		
		if (count($recipients) === 1) {
			return $recipients[0];
		}
		
		return $recipients;
	}
}
