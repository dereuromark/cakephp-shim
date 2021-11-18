<?php

namespace Shim\View;

use Cake\View\ViewBuilder as CoreViewBuilder;

class ShimViewBuilder extends CoreViewBuilder {

	/**
	 * Adds a helper to use.
	 *
	 * @param string $helper Helper to use.
	 * @param array<string, mixed> $options Options.
	 * @return $this
	 * @since 4.1.0
	 */
	public function addHelper(string $helper, array $options = [])
	{
		[$plugin, $name] = pluginSplit($helper);
		if ($plugin) {
			$options['class'] = $helper;
			$options['config'] = $options;
		}

		$this->_helpers[$name] = $options;

		return $this;
	}

	/**
	 * Adds helpers to use by merging with existing ones.
	 *
	 * @param array $helpers Helpers to use.
	 * @return $this
	 * @since 4.3.0
	 */
	public function addHelpers(array $helpers)
	{
		foreach ($helpers as $helper => $config) {
			if (is_int($helper)) {
				$helper = $config;
				$config = [];
			}
			$this->addHelper($helper, $config);
		}

		return $this;
	}
	/**
	 * Sets the helpers to use.
	 *
	 * @param array $helpers Helpers to use.
	 * @return $this
	 */
	public function setHelpers(array $helpers, bool $merge = true)
	{
		if ($merge) {
			deprecationWarning('The $merge param is deprecated, use addHelper()/addHelpers() instead.');
		} else {
			$this->_helpers = [];
		}

		foreach ($helpers as $helper => $config) {
			if (is_int($helper)) {
				$helper = $config;
				$config = [];
			}
			$this->addHelper($helper, $config);
		}

		return $this;
	}

}
