<?php
use \ColdTrick\Forms\Definition;

class Form extends \ElggObject {
	
	const SUBTYPE = 'form';
	
	/**
	 * @var Definition
	 */
	protected $definition;
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::initializeAttributes()
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$site = elgg_get_site_entity();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['owner_guid'] = $site->getGUID();
		$this->attributes['container_guid'] = $site->getGUID();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::getURL()
	 */
	public function getURL() {
		
		if (!empty($this->friendly_url)) {
			return "forms/{$this->friendly_url}";
		}
		
		return "forms/view/{$this->getGUID()}";
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::canComment()
	 */
	public function canComment($user_guid = 0, $default = null) {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::__clone()
	 */
	public function __clone() {
		parent::__clone();
		
		$this->attributes['time_created'] = null;
		$this->title = elgg_echo('forms:entity:clone:title', [$this->title]);
		$this->friendly_url = forms_generate_valid_friendly_url("{$this->friendly_url}-copy");
	}
	
	/**
	 * Get the form definition
	 *
	 * @return \ColdTrick\Forms\Definition
	 */
	public function getDefinition() {
		if (!isset($this->definition)) {
			$this->definition = new Definition($this);
		}
		
		return $this->definition;
	}
	
	/**
	 * Get the endpoint configuration
	 *
	 * @param string $endpoint (optional) the endpoint to get the config for
	 *
	 * @return array
	 */
	public function getEndpointConfig($endpoint = null) {
		
		if (empty($this->endpoint_config)) {
			return [];
		}
		
		$endpoint_config = json_decode($this->endpoint_config, true);
		if (!isset($endpoint)) {
			return $endpoint_config;
		}
		
		return elgg_extract($endpoint, $endpoint_config, []);
	}
}
