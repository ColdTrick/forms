<?php

namespace ColdTrick\Forms\Endpoint;

use ColdTrick\Forms\Endpoint;
use ColdTrick\Forms\Result;
use Elgg\Email;

class Csv extends Endpoint {

	const FILENAME = 'results.csv';
	
	/**
	 * {@inheritDoc}
	 */
	public function process(Result $result) {
		
		$headers = [];
		$values = [];
		
		foreach ($result->getPages() as $page) {
			foreach ($page->getSections() as $section) {
				foreach ($section->getFields() as $field) {
					if ($field->getType() === 'file') {
						// not supported
						continue;
					}
					
					$headers[] = $field->getLabel();
					$values[] = $field->getValue();
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
			fputcsv($fh, $headers, ';');
		}
		
		// add values
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
		
		$email = Email::factory([
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
