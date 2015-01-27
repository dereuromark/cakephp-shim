<?php

App::uses('Component', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('Hash', 'Utility');

/**
 * A component included in every app to take care of common stuff.
 *
 * @author Mark Scherer
 * @copyright 2012 Mark Scherer
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class ShimComponent extends Component {

	/**
	 * Trigger a warning about named param leftovers.
	 * But don't care about search engine hits or alike, that still
	 * have the old URL.
	 *
	 * @return void
	 */
	public function startup(Controller $Controller = null) {
		// Deprecation notices, but only for internally triggered ones
		if (Configure::read('App.warnAboutNamedParams')) {
			if (!empty($Controller->request->params['named']) && ($referer = $Controller->request->referer(true)) && $referer !== '/') {
				trigger_error('Named params ' . json_encode($Controller->request->params['named']) . ' - from ' . $referer, E_USER_DEPRECATED);
			}
		}
	}

}
