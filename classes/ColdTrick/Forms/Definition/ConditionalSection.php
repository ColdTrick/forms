<?php

namespace ColdTrick\Forms\Definition;

class ConditionalSection extends Section {
	
	/**
	 * Get the matching value for this conditional section
	 *
	 * @return string
	 */
	public function getValue() {
		return elgg_extract('value', $this->config);
	}
}
