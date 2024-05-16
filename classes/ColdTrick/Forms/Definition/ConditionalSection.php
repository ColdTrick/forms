<?php

namespace ColdTrick\Forms\Definition;

/**
 * Conditional Form Section
 */
class ConditionalSection extends Section {
	
	/**
	 * Get the matching value for this conditional section
	 *
	 * @return null|string
	 */
	public function getValue(): ?string {
		return elgg_extract('value', $this->config);
	}
}
