<?php

namespace TestApp\Controller\Component;

use Shim\Controller\Component\RequestHandlerComponent;

class RequestHandlerExtComponent extends RequestHandlerComponent {

	/**
	 * @return string|null
	 */
	public function getExt(): ?string {
		return $this->ext;
	}

	/**
	 * @param string|null $ext
	 *
	 * @return void
	 */
	public function setExt(?string $ext): void {
		$this->ext = $ext;
	}

}
