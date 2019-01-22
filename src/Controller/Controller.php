<?php
namespace Shim\Controller;

use Cake\Controller\Controller as CoreController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;
use Exception;

/**
 * DRY Controller stuff
 *
 * @property \Shim\Controller\Component\SessionComponent $Session
 */
class Controller extends CoreController {

	/**
	 * Sets a number of properties based on conventions if they are empty. To override the
	 * conventions CakePHP uses you can define properties in your class declaration.
	 *
	 * @param \Cake\Http\ServerRequest|null $request Request object for this controller. Can be null for testing,
	 *   but expect that features that use the request parameters will not work.
	 * @param \Cake\Http\Response|null $response Response object for this controller.
	 * @param string|null $name Override the name useful in testing when using mocks.
	 * @param \Cake\Event\EventManager|null $eventManager The event manager. Defaults to a new instance.
	 * @param \Cake\Controller\ComponentRegistry|null $components The component registry. Defaults to a new instance.
	 */
	public function __construct(ServerRequest $request = null, Response $response = null, $name = null, $eventManager = null, $components = null) {
		if (!isset($this->modelClass) && !empty($this->uses)) {
			$uses = (array)$this->uses;
			$model = array_shift($uses);
			$modelClass = Inflector::pluralize($model);
			$this->modelClass = $modelClass;

			trigger_error('Use $modelClass instead of $uses property.', E_USER_DEPRECATED);
		}

		parent::__construct($request, $response, $name, $eventManager, $components);
	}

	/**
	 * Add headers for IE8 etc to fix caching issues in those stupid browsers
	 *
	 * @return void
	 */
	public function disableCache() {
		$this->response = $this->response
			->withHeader('Pragma', 'no-cache')
			->withDisabledCache();
	}

	/**
	 * @param \Cake\Event\Event $event
	 * @return \Cake\Http\Response|null
	 */
	public function beforeRender(Event $event) {
		parent::beforeRender($event);

		// Automatically shim $this->request->data = $this->Model->find() etc which used to be of type array
		/** @var \Cake\ORM\Entity|null $data */
		$data = $this->request->getData();
		if ($data && $data instanceof Entity) {
			$this->request = $this->request->withParsedBody($data->toArray());
		}
	}

	/**
	 * Hook to monitor headers being sent.
	 *
	 * This, if desired, adds a check if your controller actions are cleanly built and no headers
	 * or output is being sent prior to the response class, which should be the only one doing this.
	 *
	 * @param \Cake\Event\Event $event An Event instance
	 * @throws \Exception
	 * @return \Cake\Http\Response|null
	 */
	public function afterFilter(Event $event) {
		if (Configure::read('Shim.monitorHeaders') && $this->name !== 'Error' && PHP_SAPI !== 'cli') {
			if (headers_sent($filename, $lineNumber)) {
				$message = sprintf('Headers already sent in %s on line %s', $filename, $lineNumber);
				if (Configure::read('debug')) {
					throw new Exception($message);
				}
				trigger_error($message);
			}
		}
	}

}
