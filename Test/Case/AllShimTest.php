<?php
/**
 * Shim Plugin - All plugin tests
 */
class AllShimTest extends PHPUnit_Framework_TestSuite {

	/**
	 * Suite method, defines tests for this suite.
	 *
	 * @return void
	 */
	public static function suite() {
		$Suite = new CakeTestSuite('All Shim tests');

		$path = dirname(__FILE__);
		$Suite->addTestDirectoryRecursive($path . DS . 'Model');
		$Suite->addTestDirectoryRecursive($path . DS . 'View');
		$Suite->addTestDirectoryRecursive($path . DS . 'Controller');
		$Suite->addTestDirectoryRecursive($path . DS . 'Routing');
		$Suite->addTestDirectoryRecursive($path . DS . 'TestSuite');

		return $Suite;
	}

}
