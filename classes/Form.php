<?php
use \ColdTrick\Forms\Definition;

class Form extends \ElggObject {
	
	const SUBTYPE = 'form';
	
	/**
	 *
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
	
	public function getDefinition() {
		if (!isset($this->definition)) {
			$this->definition = new Definition($this);
		}
		
		return $this->definition;
	}
}
