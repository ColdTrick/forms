<?php

namespace ColdTrick\Forms\Menus;

use ColdTrick\Forms\Endpoint\Csv;

class Entity {
	
	/**
	 * Add menu items to the form entity menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerForm(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!($entity instanceof \Form) || !$entity->canEdit()) {
			return;
		}
		
		$return_value = $hook->getValue();
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'compose',
			'icon' => 'list',
			'text' => elgg_echo('forms:entity_menu:compose'),
			'href' => elgg_generate_entity_url($entity, 'compose'),
		]);
		
		if ($entity->hasDefinition()) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'export',
				'icon' => 'download',
				'text' => elgg_echo('export'),
				'href' => elgg_generate_action_url('forms/definition/export', ['guid' => $entity->guid]),
				'is_action' => true,
			]);
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'copy',
			'icon' => 'copy',
			'text' => elgg_echo('forms:entity_menu:copy'),
			'href' => elgg_generate_action_url('forms/copy', ['guid' => $entity->guid]),
			'confirm' => elgg_echo('forms:entity_menu:copy:confirm'),
		]);
		
		return $return_value;
	}
	
	/**
	 * Add a download link to download the CSV file of a form
	 *
	 * @param \Elgg\Hook $hook 'regsiter', 'menu:entity'
	 */
	public static function addCsvDownload(\Elgg\Hook $hook) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \Form || elgg_in_context('compose')) {
			return;
		}
		
		$endpoint = $entity->getEndpoint();
		if (!$endpoint instanceof Csv) {
			// only for CSV endpoints
			return;
		}
		
		$file = $endpoint->getFile($entity);
		if (!$file->exists()) {
			return;
		}
		
		if (!$entity->canEdit()) {
			// check if current user is allowd to download
			$endpoint_config = $entity->getEndpointConfig($entity->endpoint);
			$downloaders = (array) elgg_extract('downloaders', $endpoint_config, []);
			if (!in_array(elgg_get_logged_in_user_guid(), $downloaders)) {
				return;
			}
		}
		
		/* @var $result \Elgg\Menu\MenuItems */
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'download_csv',
			'icon' => 'download',
			'text' => elgg_echo('forms:endpoint:csv:download'),
			'href' => $file->getDownloadURL(),
		]);
		
		return $result;
	}
	
	/**
	 * Add a clear link to remove the CSV file of a form
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 */
	public static function addCsvClear(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \Form || !$entity->canEdit() || elgg_in_context('compose')) {
			return;
		}
		
		$endpoint = $entity->getEndpoint();
		if (!$endpoint instanceof Csv) {
			// only for CSV endpoints
			return;
		}
		
		$file = $endpoint->getFile($entity);
		if (!$file->exists()) {
			return;
		}
		
		/* @var $result \Elgg\Menu\MenuItems */
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'clear_csv',
			'icon' => 'trash-alt',
			'text' => elgg_echo('forms:endpoint:csv:clear'),
			'href' => elgg_generate_action_url('forms/endpoints/csv/clear', [
				'guid' => $entity->guid,
			]),
			'confirm' => elgg_echo('forms:endpoint:csv:clear:confirm'),
		]);
		
		return $result;
	}
}
