<?php

namespace TestApp\Controller;

use Cake\Controller\Controller;

/**
 * RequestHandlerTestController class
 */
class RequestHandlerTestController extends Controller {

	/**
	 * test method for AJAX redirection
	 *
	 * @return void
	 */
	public function destination(): void {
		$this->viewBuilder()->setTemplatePath('Posts');
		$this->render('index');
	}

	/**
	 * test method for testing that response type set in action doesn't get
	 * overridden by RequestHandlerComponent::beforeRender()
	 *
	 * @return void
	 */
	public function setResponseType(): void {
		$this->response = $this->response->withType('txt');
	}

}
