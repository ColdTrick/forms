<?php

namespace ColdTrick\Forms\Endpoint;

use ColdTrick\Forms\Endpoint;
use ColdTrick\Forms\Result;
use Elgg\Email as ElggMail;
use Elgg\Values;
use ColdTrick\Forms\Definition\Field;

class Csv extends Endpoint {

	const FILENAME = 'results.csv';
	
	/**
	 * {@inheritDoc}
	 */
	public function process(Result $result) {
		
		$headers = [];
		$values = [];
		
		$add_field_value = function(Field $field) use (&$headers, &$values) {
			$value = $field->getValue();
			
			switch ($field->getType()) {
				case 'file':
					return;
				case 'longtext':
				case 'plaintext':
					$value = str_replace('\r', '', $value);
					break;
				default:
					break;
			}
			
			if (is_array($value)) {
				$value = implode(',', $value);
			}
			
			$headers[] = $field->getLabel();
			$values[] = trim($value);
		};
		
		foreach ($result->getPages() as $page) {
			foreach ($page->getSections() as $section) {
				foreach ($section->getFields() as $field) {
					$add_field_value($field);
					
					foreach ($field->getConditionalSections() as $conditional_section) {
						foreach ($conditional_section->getFields() as $conditional_field) {
							$add_field_value($conditional_field);
						}
					}
				}
			}
		}
		
		// check for completely empty forms
		$filtered = array_filter($values);
		if (empty($filtered)) {
			return false;
		}
		
		$form = $result->getForm();
		
		$file = $this->getFile($form);
		
		$exists = $file->exists();
		
		$fh = $file->open($exists ? 'append' : 'write');
		
		if (!$exists) {
			// add header row
			array_unshift($headers, 'Time'); // add time header
			fputcsv($fh, $headers, ';');
		}
		
		// add values
		array_unshift($values, date('Y-m-d H:i:s')); // add time value
		fputcsv($fh, $values, ';');
		
		$file->close();
		
		$this->sendNotification($form);
	}
	
	/**
	 * Return an ElggFile for this endpoint
	 *
	 * @return \ElggFile
	 */
	public function getFile(\Form $form) {
		$file = new \ElggFile();
		$file->owner_guid = $form->guid;
		$file->setFilename(self::FILENAME);
		
		return $file;
	}
	
	/**
	 * Send a notification that a form was filled in
	 *
	 * @return void
	 */
	protected function sendNotification(\Form $form) {
		$to = $this->getConfig('to');
		if (empty($to) || !is_email_address($to)) {
			return;
		}
		
		$email = ElggMail::factory([
			'to' => $to,
			'subject' => elgg_echo('forms:endpoint:csv:notification:subject', [$form->getDisplayName()]),
			'body' => elgg_echo('forms:endpoint:csv:notification:body', [
				$form->getDisplayName(),
				$form->getURL(),
			]),
			'params' => [
				'action' => 'submit',
				'object' => $form,
			],
		]);
		
		elgg_send_email($email);
	}
}
