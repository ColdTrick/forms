<?php

namespace ColdTrick\Forms\Endpoint;

use ColdTrick\Forms\Endpoint;
use ColdTrick\Forms\Result;
use ColdTrick\Forms\Definition\Field;
use Elgg\Email\Address;

/**
 * Email Endpoint
 */
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
	 * {@inheritdoc}
	 */
	public function __construct(array $config) {
		parent::__construct($config);
		
		$this->addRecipient('to', elgg_extract('to', $config));
		$this->addRecipient('cc', elgg_extract('cc', $config));
		$this->addRecipient('bcc', elgg_extract('bcc', $config));
		
		$user = elgg_get_logged_in_user_entity();
		if (elgg_extract('cc_user', $config) && ($user instanceof \ElggUser)) {
			$this->addRecipient('cc', $user->email);
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function process(Result $result): bool {
		$this->result = $result;
		
		$form = $this->result->getForm();
		
		$body = $this->getBody();
		
		// set recipients after body processing because some can be added
		$email = \Elgg\Email::factory([
			'from' => $this->getFrom(),
			'to' => $this->getRecipients('to'),
			'cc' => $this->getRecipients('cc'),
			'bcc' => $this->getRecipients('bcc'),
			'subject' => elgg_echo('forms:endpoint:email:subject', [$form->getDisplayName()]),
			'body' => $body,
			'params' => $this->getParams(),
		]);
						
		return elgg_send_email($email);
	}
	
	/**
	 * Build the email body
	 *
	 * @return string
	 */
	protected function getBody(): string {
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
	 * @param Field $field field
	 *
	 * @return string
	 */
	protected function getBodyField(Field $field): string {
		$row = elgg_format_element('td', ['style' => 'vertical-align: top; width: 200px;'], $field->getLabel() . ': ');
		
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
		
		$row .= elgg_format_element('td', ['style' => 'vertical-align: top'], (string) $value);
		$row .= PHP_EOL;
		
		return elgg_format_element('tr', [], $row);
	}
	
	/**
	 * Get the from email address
	 *
	 * @return \Elgg\Email\Address
	 */
	protected function getFrom(): Address {
		$site = elgg_get_site_entity();
		
		return new Address($site->getEmailAddress(), $site->getDisplayName());
	}
	
	/**
	 * Get additional email params
	 *
	 * @return array
	 */
	protected function getParams(): array {
		$result = [];
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
	protected function addAttachment(Field $field): void {
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
				'type' => $uploaded_file->getMimeType(),
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
	protected function addRecipient(string $type, string $address): void {
		if (!in_array($type, ['to', 'cc', 'bcc'])) {
			return;
		}
		
		if (!elgg_is_valid_email($address)) {
			return;
		}
		
		$this->recipients[$type][] = new Address($address);
	}
	
	/**
	 * Add an email field to the configured recipient
	 *
	 * @param Field $field the field to check
	 *
	 * @return void
	 */
	protected function addRecipientFromField(Field $field): void {
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
	 * @param string $type  to, cc or bcc
	 * @param int    $limit number of recipients to return
	 *
	 * @return null|Address|Address[]
	 */
	protected function getRecipients(string $type, int $limit = 0) {
		if (!in_array($type, ['to', 'cc', 'bcc'])) {
			return null;
		}
		
		$recipients = elgg_extract($type, $this->recipients, []);
		if (empty($recipients)) {
			return null;
		}
		
		if ($limit > 0) {
			$recipients = array_slice($recipients, 0, $limit);
		}
		
		if (count($recipients) === 1) {
			return $recipients[0];
		}
		
		return $recipients;
	}
}
