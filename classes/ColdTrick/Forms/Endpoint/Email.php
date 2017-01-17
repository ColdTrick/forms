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
	 * {@inheritDoc}
	 * @see \ColdTrick\Forms\Endpoint::process()
	 */
	public function process(Result $result) {
		$this->result = $result;
		
		$form = $this->result->getForm();
		
		$to = $this->getConfig('to');
		$from = $this->getFrom();
		
		
		$subject = elgg_echo('forms:endpoint:email:subject', [$form->getDisplayName()]);
		$body = $this->getBody();
		
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
					
					$field_content[] = $this->getBodyField($field);
					
					// add the conditional sections based on the value of the field
					foreach ($field->getConditionalSections(true) as $conditional_section) {
						
						foreach ($conditional_section->getFields() as $conditional_field) {
							
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
			
			$body .= elgg_format_element('h3', [], $page->getTitle());
			$body .= PHP_EOL;
			$body .= implode(PHP_EOL, $section_content);
		}
		
		return $body;
	}
	
	/**
	 * Format a field for in the mail message
	 *
	 * @param Field $field
	 *
	 * @return string
	 */
	protected function getBodyField(Field $field) {
		
		$row = elgg_format_element('td', [], $field->getLabel() . ': ');
		$row .= elgg_format_element('td', [], $field->getValue());
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
		
		return [
			'cc' => $this->getConfig('cc'),
			'bcc' => $this->getConfig('bcc'),
		];
	}
}
