<?php

namespace ColdTrick\Forms\Plugins;

/**
 * Support for CSV Exporter
 */
class CSVExporter {
	
	/**
	 * Register for csv export
	 *
	 * @param \Elgg\Event $event 'allowed_type_subtypes', 'csv_exporter'
	 *
	 * @return array
	 */
	public static function register(\Elgg\Event $event): array {
		$result = $event->getValue();
		
		$result['object'][] = \Form::SUBTYPE;
		
		return $result;
	}
}
