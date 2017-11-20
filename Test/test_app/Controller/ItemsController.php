<?php
App::uses('Controller', 'Controller');

class ItemsController extends Controller {

	/**
	 * @var array
	 */
	public $uses = [];

	/**
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

	/**
	 * @return void
	 */
	public function index() {
	}

	/**
	 * @return void
	 */
	public function session() {
		$uid = $this->Session->read('Auth.User.id');
		if ($uid != 1) {
			throw new MethodNotAllowedException('No access');
		}
		//For fun we change it
		$this->Session->write('Auth.User.id', 2);
		$this->autoRender = false;
	}

	/**
	 * @return void
	 */
	public function posting() {
		$this->request->allowMethod('post');

		$key = $this->request->data['key'];
		if (!$key) {
			throw new InternalErrorException();
		}
		$this->autoRender = false;
	}

	/**
	 * @return void
	 */
	public function exceptional() {
		throw new NotFoundException('Not really found');
	}

	/**
	 * @return void
	 */
	public function redirecting() {
		$this->Session->setFlash('yeah');
		return $this->redirect('/foobar');
	}

}
