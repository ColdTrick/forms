<?php

namespace ColdTrick\Forms\Menus;

use ColdTrick\Forms\Endpoint\Csv;

class Title {
	
	/**
	 * Add a download button to download the CSV file of a form
	 *
	 * @param \Elgg\Hook $hook 'regsiter', 'menu:title'
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
		
		if (!$entity->canEdit()) {
			// check if current user is allowd to download
			$endpoint_config = $entity->getEndpointConfig($entity->endpoint);
			$downloaders = (array) elgg_extract('downloaders', $endpoint_config, []);
			if (!in_array(elgg_get_logged_in_user_guid(), $downloaders)) {
				return;
			}
		}
		
		$file = $endpoint->getFile($entity);
		if (!$file->exists()) {
			return;
		}
		
		/* @var $result \Elgg\Menu\MenuItems */
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'download_csv',
			'icon' => 'download',
			'text' => elgg_echo('download'),
			'href' => $file->getDownloadURL(),
			'link_class' => 'elgg-button elgg-button-action',
		]);
		
		return $result;
	}
}
