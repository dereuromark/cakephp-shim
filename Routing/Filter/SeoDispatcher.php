<?php
App::uses('DispatcherFilter', 'Routing');
App::uses('Inflector', 'Utility');
App::uses('Configure', 'Core');
App::uses('Router', 'Routing');

/**
 * Dispatcher to clean out invalid controller/action calls.
 */
class SeoDispatcher extends DispatcherFilter {

	/**
	 * Default priority for all methods in this filter
	 * This filter should run after the request gets parsed by router
	 *
	 * @var int
	 */
	public $priority = 11;

	/**
	 * The request fields to process
	 *
	 * @var array
	 */
	public $fields = ['prefix', 'plugin', 'controller', 'action', 'ext'];

	/**
	 * Checks if a valid URL has been requested.
	 *
	 * E.g. valid:
	 * - /admin/plugin_name/controller_name/action_name
	 *
	 * All invalid, but unfortunately working wihtout this dispatcher:
	 * - /admin/pluginName/controllerName/actionName
	 * - /ControllerName/Action_name
	 * - etc
	 *
	 * @param CakeEvent $event containing the request and response object
	 * @return void|Response
	 * @throws NotFoundException When URL invalid
	 */
	public function beforeDispatch(CakeEvent $event) {
		$request = $event->data['request'];
		$fields = $this->fields;

		// Convention is snake_cased URL pieces
		$urlPieces = [];
		$ok = true;
		foreach ($fields as $field) {
			if (empty($request->params[$field])) {
				continue;
			}
			$value = $request->params[$field];
			$correctInflection = Inflector::underscore($value);
			$urlPieces[$field] = $correctInflection;
			if ($value === $correctInflection) {
				continue;
			}
			$ok = false;
		}

		if ($ok) {
			return;
		}

		// For debugging without this dispatcher - only for debug mode
		if (Configure::read('debug') && $request->query('skip_seo')) {
			return;
		}

		$urlPieces += ['?' => $request->query] + $request->pass + $request->named;

		$url = Router::url($urlPieces, true);

		if (Configure::read('Shim.handleSeo') === 'exception') {
			throw new NotFoundException('URL should be: ' . $url);
		}
		$response = $event->data['response'];
		$event->stopPropagation();

		// We only use 301 in productive mode as the browser "remembers" them and makes debugging painful in local dev.
		$status = Configure::read('debug') ? 302 : 301;
		$response->statusCode($status);
		$response->header('Location', $url);

		return $response;
	}

}
