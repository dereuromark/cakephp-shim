<?php
namespace Shim\Controller;

use Cake\Controller\Controller as CakeController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;

/**
 * DRY Controller stuff
 */
class Controller extends CakeController {

	/**
	 * Add headers for IE8 etc to fix caching issues in those stupid browsers
	 *
	 * @return void
	 */
	public function disableCache() {
		$this->response->header([
			'Pragma' => 'no-cache',
		]);
		$this->response->disableCache();
	}

	/**
	 * Handles automatic pagination of model records.
	 *
	 * @overwrite to support defaults like limit, querystring settings
	 * @param \Cake\ORM\Table|string|\Cake\ORM\Query $object Table to paginate
	 *   (e.g: Table instance, 'TableName' or a Query object)
	 * @return \Cake\ORM\ResultSet Query results
	 */
	public function paginate($object = null) {
		if ($defaultSettings = (array)Configure::read('Paginator')) {
			$this->paginate += $defaultSettings;
		}
		return parent::paginate($object);
	}

	/**
	 * @param Event $event
	 * @return void
	 */
	public function beforeRender(Event $event) {
		parent::beforeRender($event);

		// Automatically shim $this->request->data = $this->Model->find() etc which used to be of type array
		if (!empty($this->request->data) && $this->request->data instanceof Entity) {
			$this->request->data = $this->request->data->toArray();
		}
	}

}
