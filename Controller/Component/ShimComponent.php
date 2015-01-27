<?php

App::uses('Component', 'Controller');

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
		if (!Configure::read('App.warnAboutNamedParams')) {
			return;
		}

		// Deprecation notices, but only for internally triggered ones
		if (
			$Controller->name !== 'CakeError' && !empty($Controller->request->params['named'])
			&& ($referer = $Controller->request->referer(true)) && $referer !== '/'
		) {
			$message = 'Named params ' . json_encode($Controller->request->params['named']) . ' - from ' . $referer;
			if (Configure::read('debug')) {
				throw new CakeException($message);
			}
			trigger_error($message, E_USER_DEPRECATED);
		}
	}

}
