<?php

namespace ColdTrick\Forms\Endpoint;

use ColdTrick\Forms\Endpoint;
use ColdTrick\Forms\Result;
use Elgg\Email as ElggMail;
use Elgg\Values;
use ColdTrick\Forms\Definition\Field;

/**
 * CSV Endpoint
 */
class Csv extends Endpoint {

	const FILENAME = 'results.csv';
	
	/**
	 * {@inheritdoc}
	 */
	public function process(Result $result): bool {
		$headers = [];
		$values = [];
		
		$add_field_value = function(Field $field) use (&$headers, &$values) {
			$value = $field->getValue();
			
			switch ($field->getType()) {
				case 'file':
					return;
				case 'longtext':
				case 'plaintext':
					$value = str_replace('\r', '', (string) $value);
					break;
				default:
					break;
			}
			
			if (is_array($value)) {
				$value = implode(',', $value);
			}
			
			$headers[] = $field->getLabel();
			$values[] = trim((string) $value);
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
		
		return true;
	}
	
	/**
	 * Return an ElggFile for this endpoint
	 *
	 * @param \Form $form form to get file for
	 *
	 * @return \ElggFile
	 */
	public function getFile(\Form $form): \ElggFile {
		$file = new \ElggFile();
		$file->owner_guid = $form->guid;
		$file->setFilename(self::FILENAME);
		
		return $file;
	}
	
	/**
	 * Send a notification that a form was filled in
	 *
	 * @param \Form $form notification related form
	 *
	 * @return null|bool
	 */
	protected function sendNotification(\Form $form): ?bool {
		$to = $this->getConfig('to');
		if (empty($to) || !elgg_is_valid_email((string) $to)) {
			return null;
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
		
		return elgg_send_email($email);
	}
}
