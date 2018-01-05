<?php
/**
 * PasswordHasherTest file
 */
App::uses('PasswordHasherFactory', 'Shim.Controller/Component/Auth');
App::uses('ShimTestCase', 'Shim.TestSuite');
App::uses('AbstractPasswordHasher', 'Controller/Component/Auth');

/**
 * Test case for DirectAuthentication
 */
class PasswordHasherFactoryTest extends ShimTestCase {

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * PasswordHasherFactoryTest::testBuild()
	 *
	 * @return void
	 */
	public function testBuildSimple() {
		$result = PasswordHasherFactory::build('Simple');
		$this->assertInstanceOf('SimplePasswordHasher', $result);
	}

	/**
	 * PasswordHasherFactoryTest::testBuild()
	 *
	 * @return void
	 */
	public function testBuildComplex() {
		$result = PasswordHasherFactory::build('Shim.Complex');
		$this->assertInstanceOf('ComplexPasswordHasher', $result);
	}

}

class ComplexPasswordHasher extends AbstractPasswordHasher {

	public function hash($pwd) {
	}

	public function check($pwd, $hashedPwd) {
	}

}
