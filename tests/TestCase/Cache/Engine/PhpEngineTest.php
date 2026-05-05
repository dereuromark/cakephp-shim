<?php

declare(strict_types=1);

namespace Shim\Test\TestCase\Cache\Engine;

use Cake\Cache\Cache;
use Cake\TestSuite\TestCase;
use LogicException;
use Shim\Cache\Engine\PhpEngine;
use stdClass;

class PhpEngineTest extends TestCase {

	/**
	 * @var string
	 */
	protected string $cachePath;

	protected function setUp(): void {
		parent::setUp();

		$this->cachePath = TMP . 'tests' . DS . 'php_cache' . DS;
		if (!is_dir($this->cachePath)) {
			mkdir($this->cachePath, 0777, true);
		}

		Cache::setConfig('php_test', [
			'className' => PhpEngine::class,
			'path' => $this->cachePath,
			'prefix' => 'cake_',
			'duration' => 3600,
		]);
	}

	protected function tearDown(): void {
		parent::tearDown();

		Cache::drop('php_test');
		Cache::drop('php_test_expiry');

		if (!is_dir($this->cachePath)) {
			return;
		}

		$files = glob($this->cachePath . '*');
		if ($files === false) {
			return;
		}

		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);

				continue;
			}

			if (is_dir($file)) {
				$this->_deleteDir($file);
			}
		}
	}

	public function testSetAndGet(): void {
		$result = Cache::write('test_key', 'test_value', 'php_test');
		$this->assertTrue($result);

		$value = Cache::read('test_key', 'php_test');
		$this->assertSame('test_value', $value);
	}

	public function testSetAndGetArray(): void {
		$data = ['foo' => 'bar', 'nested' => ['a' => 1, 'b' => 2]];

		Cache::write('array_key', $data, 'php_test');
		$result = Cache::read('array_key', 'php_test');

		$this->assertSame($data, $result);
	}

	public function testSetAndGetObject(): void {
		$data = new stdClass();
		$data->name = 'test';
		$data->value = 42;

		Cache::write('object_key', $data, 'php_test');
		$result = Cache::read('object_key', 'php_test');

		$this->assertEquals($data, $result);
	}

	public function testDelete(): void {
		Cache::write('delete_key', 'value', 'php_test');
		$this->assertSame('value', Cache::read('delete_key', 'php_test'));

		$result = Cache::delete('delete_key', 'php_test');
		$this->assertTrue($result);
		$this->assertNull(Cache::read('delete_key', 'php_test'));
	}

	public function testClear(): void {
		Cache::write('key1', 'value1', 'php_test');
		Cache::write('key2', 'value2', 'php_test');

		$result = Cache::clear('php_test');
		$this->assertTrue($result);
		$this->assertNull(Cache::read('key1', 'php_test'));
		$this->assertNull(Cache::read('key2', 'php_test'));
	}

	public function testCacheFileIsValidPhp(): void {
		Cache::write('php_key', ['test' => 'data'], 'php_test');

		$files = glob($this->cachePath . 'cake_*');
		$this->assertIsArray($files);
		$this->assertNotEmpty($files);

		$value = require $files[0];
		$this->assertSame(['test' => 'data'], $value);
	}

	public function testExpiration(): void {
		Cache::setConfig('php_test_expiry', [
			'className' => PhpEngine::class,
			'path' => $this->cachePath,
			'prefix' => 'cake_expiry_',
			'duration' => 1,
		]);

		Cache::write('expiring_key', 'value', 'php_test_expiry');
		$this->assertSame('value', Cache::read('expiring_key', 'php_test_expiry'));

		sleep(2);

		$this->assertNull(Cache::read('expiring_key', 'php_test_expiry'));
	}

	public function testSpecialCharactersInKey(): void {
		Cache::write('key/with:special.chars', 'value', 'php_test');
		$this->assertSame('value', Cache::read('key/with:special.chars', 'php_test'));
	}

	public function testIncrementThrowsException(): void {
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('PhpEngine does not support atomic increment');

		$engine = new PhpEngine();
		$engine->init(['path' => $this->cachePath]);
		$engine->increment('key');
	}

	public function testDecrementThrowsException(): void {
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('PhpEngine does not support atomic decrement');

		$engine = new PhpEngine();
		$engine->init(['path' => $this->cachePath]);
		$engine->decrement('key');
	}

	/**
	 * @param string $path
	 * @return void
	 */
	protected function _deleteDir(string $path): void {
		$entries = glob($path . DS . '*');
		if ($entries !== false) {
			foreach ($entries as $entry) {
				if (is_dir($entry)) {
					$this->_deleteDir($entry);

					continue;
				}

				unlink($entry);
			}
		}

		rmdir($path);
	}

}
