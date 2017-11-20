<?php
App::uses('JsonView', 'View');

/**
 * Class JsonShimView
 *
 * This shims the 3.x JsonView class to be same for 2.x projects, expecting the following JSON options
 * to be on by default:
 * JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT
 *
 * You can also declare your options side-wide using Configure key 'Shim.jsonOptions'.
 *
 * This aims to make the JSON output RFC compliant, see https://github.com/cakephp/cakephp/pull/2349
 */
class JsonShimView extends JsonView {

	/**
	 * @param array $serialize
	 * @return string
	 */
	protected function _serialize($serialize) {
		if (!isset($this->viewVars['_jsonOptions']) && Configure::read('Shim.jsonOptions') !== null) {
			$this->viewVars['_jsonOptions'] = Configure::read('Shim.jsonOptions');
		} elseif (!isset($this->viewVars['_jsonOptions'])) {
			$this->viewVars['_jsonOptions'] = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
		}

		$version = Configure::version();
		// For now we shim it for all
		if (true || !version_compare($version, '2.6.5', '>=')) {
			return $this->_serializeShimmed($serialize);
		}

		return parent::_serialize($serialize);
	}

	/**
	 * Note that this view only works with CakePHP >= 2.6.5.
	 * Any version below will not recognize the _jsonOptions key!
	 * So we need to shim that, too.
	 *
	 * This will be removed once CakePHP 2.7 is stable/out.
	 *
	 * @param $serialize
	 * @return string
	 */
	protected function _serializeShimmed($serialize) {
		if (is_array($serialize)) {
			$data = [];
			foreach ($serialize as $alias => $key) {
				if (is_numeric($alias)) {
					$alias = $key;
				}
				if (array_key_exists($key, $this->viewVars)) {
					$data[$alias] = $this->viewVars[$key];
				}
			}
			$data = !empty($data) ? $data : null;
		} else {
			$data = isset($this->viewVars[$serialize]) ? $this->viewVars[$serialize] : null;
		}

		$jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
		if (isset($this->viewVars['_jsonOptions'])) {
			if ($this->viewVars['_jsonOptions'] === false) {
				$jsonOptions = 0;
			} else {
				$jsonOptions = $this->viewVars['_jsonOptions'];
			}
		}
		if (Configure::read('debug')) {
			$jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;
		}

		$json = json_encode($data, $jsonOptions);

		if (function_exists('json_last_error') && json_last_error() !== JSON_ERROR_NONE) {
			throw new CakeException(json_last_error_msg());
		}
		if ($json === false) {
			throw new CakeException('Failed to parse JSON');
		}

		return $json;
	}

}
