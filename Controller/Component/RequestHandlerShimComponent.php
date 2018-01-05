<?php
App::uses('RequestHandlerComponent', 'Controller/Component');

/**
 * Better up-to-date Mobile Detection
 *
 * Requires vendor dependency https://github.com/serbanghita/Mobile-Detect
 * Best to use composer for it.
 *
 * Options:
 * - includeCore: true for still checking on the current CakePHP core first, defaults to false
 */
class RequestHandlerShimComponent extends RequestHandlerComponent {

	protected $_detector;

	/**
	 * @param ComponentCollection $collection ComponentCollection object.
	 * @param array $settings Array of settings.
	 */
	public function __construct(ComponentCollection $collection, $settings = []) {
		$settings += [
			'enableBeforeRedirect' => true,
		];
		parent::__construct($collection, $settings);
	}

	/**
	 * Handles (fakes) redirects for Ajax requests using requestAction()
	 * Modifies the $_POST and $_SERVER['REQUEST_METHOD'] to simulate a new GET request.
	 *
	 * @param Controller $controller A reference to the controller
	 * @param string|array $url A string or array containing the redirect location
	 * @param int|array|null $status HTTP Status for redirect
	 * @param bool $exit Whether to exit script, defaults to `true`.
	 * @return void
	 */
	public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {
		if (!$this->settings['enableBeforeRedirect']) {
			return;
		}

		parent::beforeRedirect($controller, $url, $status, $exit);
	}

	/**
	 * Expose the detector for custom modifications if desired
	 *
	 * @return \Detection\MobileDetect
	 */
	public function detector() {
		if (!isset($this->_detector)) {
			$this->_detector = new \Detection\MobileDetect();
		}
		return $this->_detector;
	}

	/**
	 * Returns true if user agent string matches a mobile web browser.
	 * We overwrite the core here and use the 3.x dependency lib
	 *
	 * @return bool True if user agent is a mobile web browser.
	 */
	public function isMobile() {
		if (!empty($this->settings['includeCore']) && $this->request->is('mobile')) {
			return true;
		}

		if (!isset($this->_detector)) {
			$this->_detector = new \Detection\MobileDetect();
		}
		return $this->_detector->isMobile();
	}

	/**
	 * Detects a specific mobile device called "tablet".
	 *
	 * @return bool True if user agent is a tablet web browser.
	 */
	public function isTablet() {
		if (!isset($this->_detector)) {
			$this->_detector = new \Detection\MobileDetect();
		}
		return $this->_detector->isTablet();
	}

}
