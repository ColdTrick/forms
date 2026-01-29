<?php

namespace ColdTrick\Forms\Controllers;

use Elgg\Exceptions\Http\ValidationException;
use Elgg\Http\OkResponse;

/**
 * Form edit action
 */
class EditAction extends \Elgg\Controllers\EntityEditAction {

	/**
	 * {@inheritdoc}
	 */
	protected function sanitize(): void {
		parent::sanitize();

		$title = (string) $this->request->getParam('title');
		$friendly_url = $this->request->getParam('friendly_url', elgg_get_friendly_title($title));
		$friendly_url = elgg_get_friendly_title($friendly_url);
		
		$this->request->setParam('friendly_url', $friendly_url);
		
		$container = get_entity((int) $this->request->getParam('container_guid'));
		if (!$container instanceof \ElggGroup) {
			// set container to site
			$this->request->setParam('container_guid', 1);
		}

		if (elgg_strip_tags($this->request->getParam('thankyou') === '')) {
			$this->request->setParam('thankyou', null);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function validate(): void {
		if (!forms_is_valid_friendly_url((string) $this->request->getParam('friendly_url'), (int) $this->request->getParam('guid'))) {
			throw new ValidationException(elgg_echo('forms:action:edit:error:friendly_url'));
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function execute(array $skip_field_names = []): void {
		parent::execute($skip_field_names);

		// check for uploaded definition
		$definition = elgg_get_uploaded_file('definition');
		
		if ($this->isNewEntity() && !empty($definition)) {
			$definition_json = file_get_contents($definition->getPathname());
			$this->entity->importDefinition($definition_json);
		}

		$endpoint_config = (array) $this->request->getParam('endpoint_config', []);
		
		$this->entity->endpoint = $this->request->getParam('endpoint');
		$this->entity->endpoint_config = json_encode($endpoint_config);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(?string $forward_url = null): OkResponse {
		return parent::success(elgg_generate_url('collection:object:form:all'));
	}
}
