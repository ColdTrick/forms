<?php

namespace ColdTrick\Forms\Endpoint;

use ColdTrick\Forms\Endpoint;
use ColdTrick\Forms\Result;
use Zend\Mail\Address;

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
		return 'efeefef';
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
