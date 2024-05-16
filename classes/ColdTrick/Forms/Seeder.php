<?php

namespace ColdTrick\Forms;

use Elgg\Database\Seeds\Seed;
use Elgg\Exceptions\Seeding\MaxAttemptsException;

/**
 * Form database seeder
 */
class Seeder extends Seed {
	
	protected array $supported_field_types = [
		'checkbox',
		'checkboxes',
		'date',
		'email',
		'file',
		'hidden',
		'longtext',
		'number',
		'plaintext',
		'radio',
		'select',
		'text',
	];
	
	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());
		
		$site = elgg_get_site_entity();
		
		while ($this->getCount() < $this->limit) {
			try {
				/* @var $entity \Form */
				$entity = $this->createObject([
					'subtype' => \Form::SUBTYPE,
					'owner_guid' => $site->guid,
					'container_guid' => $site->guid,
					'thankyou' => $this->faker()->text($this->faker()->numberBetween(500, 1000)),
				]);
			} catch (MaxAttemptsException $e) {
				// unable to create a form with the given options
				continue;
			}
			
			$entity->friendly_url = elgg_get_friendly_title($entity->title);
			
			$this->configureEndpoint($entity);
			$this->addFormDefinition($entity);
			
			$this->advance();
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function unseed() {
		/* @var $entities \ElggBatch */
		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => \Form::SUBTYPE,
			'metadata_name' => '__faker',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);
		
		/* @var $entity \Form */
		foreach ($entities as $entity) {
			if ($entity->delete()) {
				$this->log("Deleted form {$entity->guid}");
			} else {
				$this->log("Failed to delete form {$entity->guid}");
				$entities->reportFailure();
				continue;
			}
			
			$this->advance();
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getType(): string {
		return \Form::SUBTYPE;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getCountOptions(): array {
		return [
			'type' => 'object',
			'subtype' => \Form::SUBTYPE,
		];
	}
	
	/**
	 * Select an endoint and make the configuration
	 *
	 * @param \Form $entity the form
	 *
	 * @return void
	 */
	protected function configureEndpoint(\Form $entity): void {
		if ($this->faker()->boolean()) {
			// email endpoint
			$entity->endpoint = 'email';
			
			$entity->endpoint_config = json_encode([
				'email' => [
					'to' => $this->getRandomEmail(),
					'cc' => '',
					'cc_user' => $this->faker()->boolean() ? '1' : '0',
					'bcc' => '',
				],
			]);
		} else {
			// csv endpoint
			$entity->endpoint = 'csv';
			
			$entity->endpoint_config = json_encode([
				'csv' => [
					'to' => $this->faker()->boolean(25) ? $this->getRandomEmail() : '',
					'downloaders' => '',
				],
			]);
		}
	}
	
	/**
	 * Add the form definition
	 *
	 * @param \Form $entity form
	 *
	 * @return void
	 */
	protected function addFormDefinition(\Form $entity): void {
		$definition = [
			'pages' => [],
		];
		
		for ($i = 0; $i < $this->faker()->numberBetween(1, 3); $i++) {
			$definition['pages'][] = $this->getPage($entity);
		}
		
		$entity->definition = json_encode($definition);
	}
	
	/**
	 * Get a page definition
	 *
	 * @param \Form $entity form
	 *
	 * @return array
	 */
	protected function getPage(\Form $entity): array {
		$page = [
			'title' => $this->faker()->sentence(),
			'sections' => [],
		];
		
		for ($i = 0; $i < $this->faker()->numberBetween(1, 3); $i++) {
			$page['sections'][] = $this->getSection($entity);
		}
		
		return $page;
	}
	
	/**
	 * Get a section definition
	 *
	 * @param \Form $entity form
	 *
	 * @return array
	 */
	protected function getSection(\Form $entity): array {
		$section = [
			'title' => $this->faker()->sentence(),
			'fields' => [],
		];
		
		for ($i = 0; $i < $this->faker()->numberBetween(2, 8); $i++) {
			$section['fields'][] = $this->getField($entity);
		}
		
		return $section;
	}
	
	/**
	 * Get a field definition
	 *
	 * @param \Form $entity form
	 *
	 * @return array
	 */
	protected function getField(\Form $entity): array {
		$key = $this->faker()->numberBetween(0, count($this->supported_field_types) - 1);
		if ($entity->endpoint === 'csv' && $this->supported_field_types[$key] === 'file') {
			// files not supported in csv forms
			while ($this->supported_field_types[$key] === 'file') {
				$key = $this->faker()->numberBetween(0, count($this->supported_field_types) - 1);
			}
		}
		
		$field = [
			'#type' => $this->supported_field_types[$key],
			'#label' => $this->faker()->sentence(),
			'#help' => $this->faker()->sentence(),
			'name' => uniqid('__field_'),
			'conditional_sections' => [], // not supported in seeding
			'required' => $this->faker()->boolean(25) ? '1' : '0',
		];
		
		$get_options = function(int $max): array {
			$options = [];
			
			for ($i = 0; $i < $max; $i++) {
				$options[] = $this->faker()->sentence(4);
			}
			
			return $options;
		};
		
		// configure additional options for certain types
		switch ($field['#type']) {
			case 'select':
				$field['multiple'] = $this->faker()->boolean(25) ? '1' : '0';
				// now add options
			case 'checkboxes':
			case 'radio':
				$field['options'] = implode(',', $get_options($this->faker()->numberBetween(3, 5)));
				
				break;
			case 'email':
				$email_recipient = [
					'',
					'to',
					'cc',
					'bcc',
				];
				$email_key = array_rand($email_recipient);
				$field['email_recipient'] = $email_recipient[$email_key];
				
				break;
			case 'hidden':
				unset($field['#help']);
				
				break;
		}
		
		return $field;
	}
}
