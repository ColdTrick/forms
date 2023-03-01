<?php

namespace ColdTrick\Forms\Menus;

use ColdTrick\Forms\Endpoint\Csv;

/**
 * Title menu callbacks
 */
class Title {
	
	/**
	 * Add a download button to download the CSV file of a form
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return array
	 */
	public static function addCsvDownload(\Elgg\Event $event) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$entity = $event->getEntityParam();
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
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'download_csv',
			'icon' => 'download',
			'text' => elgg_echo('forms:endpoint:csv:download'),
			'href' => $file->getDownloadURL(),
			'link_class' => 'elgg-button elgg-button-action',
		]);
		
		return $result;
	}
	
	/**
	 * Add a clear button to remove the CSV file of a form
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return array
	 */
	public static function addCsvClear(\Elgg\Event $event) {
		
		$entity = $event->getEntityParam();
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
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'clear_csv',
			'icon' => 'trash-alt',
			'text' => elgg_echo('forms:endpoint:csv:clear'),
			'href' => elgg_generate_action_url('forms/endpoints/csv/clear', [
				'guid' => $entity->guid,
			]),
			'link_class' => 'elgg-button elgg-button-delete',
			'confirm' => elgg_echo('forms:endpoint:csv:clear:confirm'),
		]);
		
		return $result;
	}
}
