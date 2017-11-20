<?php
/**
 * FallbackPasswordHasher file
 */
App::uses('FallbackPasswordHasher', 'Shim.Controller/Component/Auth');
App::uses('ShimTestCase', 'Shim.TestSuite');
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Model', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('PasswordHasherFactory', 'Shim.Controller/Component/Auth');

if (!defined('PASSWORD_BCRYPT')) {
	require CakePlugin::path('Shim') . 'Lib/Bootstrap/Password.php';
}

/**
 * Test case for FallbackPasswordHasher
 */
class FallbackPasswordHasherTest extends ShimTestCase {

	public $fixtures = ['core.cake_session', 'plugin.shim.tools_auth_user'];

	public $Controller;

	public $request;

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->Controller = new TestFallbackPasswordHasherController(new CakeRequest(), new CakeResponse());
		$this->Controller->constructClasses();
		$this->Controller->startupProcess();

		// Modern pwd account
		$this->Controller->TestFallbackPasswordHasherUser->create();
		$user = [
			'username' => 'itisme',
			'email' => '',
			'pwd' => 'secure123456'
		];
		$res = $this->Controller->TestFallbackPasswordHasherUser->save($user);
		$this->assertTrue((bool)$res);

		// Old pwd account
		$this->Controller->TestFallbackPasswordHasherUser->create();
		$user = [
			'username' => 'itwasme',
			'email' => '',
			'password' => Security::hash('123456', null, true)
		];
		$res = $this->Controller->TestFallbackPasswordHasherUser->save($user);
		$this->assertTrue((bool)$res);

		CakeSession::delete('Auth');

		//var_dump($this->Controller->TestFallbackPasswordHasherUser->find('all'));
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @return void
	 */
	public function testBasics() {
		$this->Controller->request->data = [
			'TestFallbackPasswordHasherUser' => [
				'username' => 'itisme',
				'password' => 'xyz'
			],
		];
		$result = $this->Controller->Auth->login();
		$this->assertFalse($result);
	}

	/**
	 * @return void
	 */
	public function testLogin() {
		$this->Controller->request->data = [
			'TestFallbackPasswordHasherUser' => [
				'username' => 'itisme',
				'password' => 'secure123456'
			],
		];
		$result = $this->Controller->Auth->login();
		$this->assertTrue($result);

		// This could be done in login() action after successfully logging in.
		$hash = $this->Controller->TestFallbackPasswordHasherUser->hash('secure123456');
		$this->assertFalse($this->Controller->TestFallbackPasswordHasherUser->needsRehash($hash));
	}

	/**
	 * @return void
	 */
	public function testLoginOld() {
		$this->Controller->request->data = [
			'TestFallbackPasswordHasherUser' => [
				'username' => 'itwasme',
				'password' => '123456'
			],
		];
		$result = $this->Controller->Auth->login();
		$this->assertTrue($result);

		// This could be done in login() action after successfully logging in.
		$hash = Security::hash('123456', null, true);
		$this->assertTrue($this->Controller->TestFallbackPasswordHasherUser->needsRehash($hash));
	}

}

class TestFallbackPasswordHasherController extends Controller {

	public $uses = ['Shim.TestFallbackPasswordHasherUser'];

	public $components = ['Auth'];

	public function beforeFilter() {
		parent::beforeFilter();

		$options = [
			'className' => 'Shim.Fallback',
			'hashers' => [
				'Shim.Modern', 'Simple'
			]
		];
		$this->Auth->authenticate = [
			'Form' => [
				'passwordHasher' => $options,
				'fields' => [
					'username' => 'username',
					'password' => 'password'
				],
				'userModel' => 'Shim.TestFallbackPasswordHasherUser'
			]
		];
	}

}

class TestFallbackPasswordHasherUser extends Model {

	public $useTable = 'tools_auth_users';

	/**
	 * TestFallbackPasswordHasherUser::beforeSave()
	 *
	 * @param array $options
	 * @return bool Success
	 */
	public function beforeSave($options = []) {
		if (!empty($this->data[$this->alias]['pwd'])) {
			$this->data[$this->alias]['password'] = $this->hash($this->data[$this->alias]['pwd']);
		}
		return true;
	}

	/**
	 * @param string $pwd
	 * @return string Hash
	 */
	public function hash($pwd) {
		$options = [
			'className' => 'Shim.Fallback',
			'hashers' => [
				'Shim.Modern', 'Simple'
			]
		];
		$passwordHasher = $this->_getPasswordHasher($options);
		return $passwordHasher->hash($pwd);
	}

	/**
	 * @param string $pwd
	 * @return bool Success
	 */
	public function needsRehash($pwd) {
		$options = [
			'className' => 'Shim.Fallback',
			'hashers' => [
				'Shim.Modern', 'Simple'
			]
		];
		$passwordHasher = $this->_getPasswordHasher($options);
		return $passwordHasher->needsRehash($pwd);
	}

	/**
	 * PasswordableBehavior::_getPasswordHasher()
	 *
	 * @param mixed $hasher Name or options array.
	 * @return PasswordHasher
	 */
	protected function _getPasswordHasher($hasher) {
		return PasswordHasherFactory::build($hasher);
	}

}
