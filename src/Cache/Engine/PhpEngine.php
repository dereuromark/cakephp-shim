<?php

declare(strict_types=1);

namespace Shim\Cache\Engine;

use Brick\VarExporter\VarExporter;
use Cake\Cache\CacheEngine;
use Cake\Cache\Event\CacheAfterDeleteEvent;
use Cake\Cache\Event\CacheAfterGetEvent;
use Cake\Cache\Event\CacheAfterSetEvent;
use Cake\Cache\Event\CacheBeforeDeleteEvent;
use Cake\Cache\Event\CacheBeforeGetEvent;
use Cake\Cache\Event\CacheBeforeSetEvent;
use Cake\Cache\Event\CacheClearedEvent;
use Cake\Cache\Event\CacheGroupClearEvent;
use DateInterval;
use LogicException;
use SplFileInfo;
use Throwable;

/**
 * PHP Cache Engine
 *
 * Stores cache data as executable PHP files using brick/varexporter.
 * This enables OPcache acceleration for fast reads on mostly static caches.
 *
 * @extends \Cake\Cache\CacheEngine<\Shim\Cache\Engine\PhpEngine>
 */
class PhpEngine extends CacheEngine {

	/**
	 * The default config used unless overridden by runtime configuration
	 *
	 * - `duration` Specify how long items in this cache configuration last.
	 *    0 means indefinite.
	 * - `groups` List of groups or 'tags' associated to every key stored in this config.
	 * - `mask` The mask used for created files
	 * - `dirMask` The mask used for created folders
	 * - `path` Path to where cache files should be saved. Defaults to system's temp dir.
	 * - `prefix` Prepended to all entries.
	 *
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
		'duration' => 0,
		'groups' => [],
		'mask' => 0664,
		'dirMask' => 0777,
		'path' => null,
		'prefix' => 'cake_',
	];

	/**
	 * True unless init/active fails.
	 *
	 * @var bool
	 */
	protected bool $_init = true;

	/**
	 * @param array<string, mixed> $config Config array.
	 * @return bool
	 */
	public function init(array $config = []): bool {
		parent::init($config);

		$this->_config['path'] ??= sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cake_php_cache' . DIRECTORY_SEPARATOR;
		if (substr($this->_config['path'], -1) !== DIRECTORY_SEPARATOR) {
			$this->_config['path'] .= DIRECTORY_SEPARATOR;
		}

		if ($this->_groupPrefix) {
			$this->_groupPrefix = str_replace('_', DIRECTORY_SEPARATOR, $this->_groupPrefix);
		}

		if (!class_exists(VarExporter::class)) {
			$this->_init = false;
			$this->warning(
				'PhpEngine requires `brick/varexporter`. Add it to your application dependencies to use this cache engine.',
			);

			return false;
		}

		return $this->_active();
	}

	/**
	 * @param string $key Identifier for the data
	 * @param mixed $value Data to be cached
	 * @param \DateInterval|int|null $ttl Optional TTL value.
	 * @return bool
	 */
	public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool {
		if (!$this->_init) {
			return false;
		}

		$duration = $this->duration($ttl);
		$key = $this->_key($key);

		$this->_eventClass = CacheBeforeSetEvent::class;
		$this->dispatchEvent(CacheBeforeSetEvent::NAME, ['key' => $key, 'value' => $value, 'ttl' => $duration]);

		$this->_eventClass = CacheAfterSetEvent::class;
		$path = $this->_path($key);

		try {
			$exported = VarExporter::export($value);
		} catch (Throwable $e) {
			$this->warning(sprintf(
				'PhpEngine failed to export value for key `%s`: %s',
				$key,
				$e->getMessage(),
			));

			$this->dispatchEvent(CacheAfterSetEvent::NAME, [
				'key' => $key, 'value' => $value, 'success' => false, 'ttl' => $duration,
			]);

			return false;
		}

		if ($duration > 0) {
			$expires = time() + $duration;
			$contents = "<?php\n// Expires: {$expires}\nreturn time() > {$expires} ? null : {$exported};\n";
		} else {
			$contents = "<?php\nreturn {$exported};\n";
		}

		$success = $this->_writeFile($path, $contents);

		$this->dispatchEvent(CacheAfterSetEvent::NAME, [
			'key' => $key, 'value' => $value, 'success' => $success, 'ttl' => $duration,
		]);

		return $success;
	}

	/**
	 * @param string $key Identifier for the data
	 * @param mixed $default Default value to return if the key does not exist.
	 * @return mixed
	 */
	public function get(string $key, mixed $default = null): mixed {
		$key = $this->_key($key);

		$this->_eventClass = CacheBeforeGetEvent::class;
		$this->dispatchEvent(CacheBeforeGetEvent::NAME, ['key' => $key, 'default' => $default]);

		$this->_eventClass = CacheAfterGetEvent::class;
		if (!$this->_init) {
			$this->dispatchEvent(CacheAfterGetEvent::NAME, ['key' => $key, 'value' => null, 'success' => false]);

			return $default;
		}

		$path = $this->_path($key);
		if (!is_file($path)) {
			$this->dispatchEvent(CacheAfterGetEvent::NAME, ['key' => $key, 'value' => null, 'success' => false]);

			return $default;
		}

		try {
			$value = require $path;
		} catch (Throwable $e) {
			$this->warning(sprintf(
				'PhpEngine failed to read cache file `%s`: %s',
				$path,
				$e->getMessage(),
			));
			$this->_deleteFile($path);

			$this->dispatchEvent(CacheAfterGetEvent::NAME, ['key' => $key, 'value' => null, 'success' => false]);

			return $default;
		}

		if ($value === null) {
			$this->_deleteFile($path);

			$this->dispatchEvent(CacheAfterGetEvent::NAME, ['key' => $key, 'value' => null, 'success' => false]);

			return $default;
		}

		$this->dispatchEvent(CacheAfterGetEvent::NAME, ['key' => $key, 'value' => $value, 'success' => true]);

		return $value;
	}

	/**
	 * @param string $key Identifier for the data
	 * @return bool
	 */
	public function delete(string $key): bool {
		$key = $this->_key($key);

		$this->_eventClass = CacheBeforeDeleteEvent::class;
		$this->dispatchEvent(CacheBeforeDeleteEvent::NAME, ['key' => $key]);

		$this->_eventClass = CacheAfterDeleteEvent::class;
		if (!$this->_init) {
			$this->dispatchEvent(CacheAfterDeleteEvent::NAME, ['key' => $key, 'success' => false]);

			return false;
		}

		$success = $this->_deleteFile($this->_path($key));

		$this->dispatchEvent(CacheAfterDeleteEvent::NAME, ['key' => $key, 'success' => $success]);

		return $success;
	}

	/**
	 * @return bool
	 */
	public function clear(): bool {
		if (!$this->_init) {
			return false;
		}

		$this->_clearDirectory($this->_config['path']);

		$this->_eventClass = CacheClearedEvent::class;
		$this->dispatchEvent(CacheClearedEvent::NAME);

		return true;
	}

	/**
	 * @param string $key The key to increment
	 * @param int $offset The number to offset
	 * @return int|false
	 */
	public function increment(string $key, int $offset = 1): int|false {
		throw new LogicException('PhpEngine does not support atomic increment.');
	}

	/**
	 * @param string $key The key to decrement
	 * @param int $offset The number to offset
	 * @return int|false
	 */
	public function decrement(string $key, int $offset = 1): int|false {
		throw new LogicException('PhpEngine does not support atomic decrement.');
	}

	/**
	 * @param string $group The group to clear.
	 * @return bool
	 */
	public function clearGroup(string $group): bool {
		$path = $this->_config['path'] . $group . DIRECTORY_SEPARATOR;
		if (is_dir($path)) {
			$this->_clearDirectory($path);
		}

		$this->_eventClass = CacheGroupClearEvent::class;
		$this->dispatchEvent(CacheGroupClearEvent::NAME, ['group' => $group]);

		return true;
	}

	/**
	 * @param string $key The cache key
	 * @return string
	 */
	protected function _path(string $key): string {
		$groups = null;
		if ($this->_groupPrefix) {
			$groups = vsprintf($this->_groupPrefix, $this->groups());
		}

		$dir = $this->_config['path'] . $groups;
		if (!is_dir($dir)) {
			mkdir($dir, $this->_config['dirMask'] ^ umask(), true);
		}

		return $dir . $key . '.php';
	}

	/**
	 * @param string $path File path
	 * @param string $content File content
	 * @return bool
	 */
	protected function _writeFile(string $path, string $content): bool {
		$dir = dirname($path);
		if (!is_dir($dir)) {
			mkdir($dir, $this->_config['dirMask'] ^ umask(), true);
		}

		$tmpFile = $path . '.tmp.' . uniqid('', true);
		if (file_put_contents($tmpFile, $content, LOCK_EX) === false) {
			return false;
		}

		chmod($tmpFile, $this->_config['mask']);

		if (!rename($tmpFile, $path)) {
			// phpcs:disable
			@unlink($tmpFile);
			// phpcs:enable

			return false;
		}

		if (function_exists('opcache_invalidate')) {
			opcache_invalidate($path, true);
		}

		return true;
	}

	/**
	 * @param string $path File path
	 * @return bool
	 */
	protected function _deleteFile(string $path): bool {
		if (!is_file($path)) {
			return false;
		}

		if (function_exists('opcache_invalidate')) {
			opcache_invalidate($path, true);
		}

		// phpcs:disable
		return @unlink($path);
		// phpcs:enable
	}

	/**
	 * @param string $path Directory path
	 * @return void
	 */
	protected function _clearDirectory(string $path): void {
		if (!is_dir($path)) {
			return;
		}

		$prefix = $this->_config['prefix'];
		$prefixLength = strlen($prefix);

		$dir = dir($path);
		if (!$dir) {
			return;
		}

		while (($entry = $dir->read()) !== false) {
			if ($entry === '.' || $entry === '..') {
				continue;
			}

			$fullPath = $path . $entry;
			if (is_dir($fullPath)) {
				$this->_clearDirectory($fullPath . DIRECTORY_SEPARATOR);
				// phpcs:disable
				@rmdir($fullPath);
				// phpcs:enable
				continue;
			}

			if (substr($entry, 0, $prefixLength) !== $prefix) {
				continue;
			}

			$this->_deleteFile($fullPath);
		}

		$dir->close();
	}

	/**
	 * @return bool
	 */
	protected function _active(): bool {
		$dir = new SplFileInfo($this->_config['path']);
		$path = $dir->getPathname();
		$success = true;

		if (!is_dir($path)) {
			// phpcs:disable
			$success = @mkdir($path, $this->_config['dirMask'] ^ umask(), true);
			// phpcs:enable
		}

		$isWritableDir = ($dir->isDir() && $dir->isWritable());
		if (!$success || ($this->_init && !$isWritableDir)) {
			$this->_init = false;
			$this->warning(sprintf(
				'%s is not writable',
				$this->_config['path'],
			));
		}

		return $success;
	}

	/**
	 * @inheritDoc
	 */
	protected function _key(string $key): string {
		$key = parent::_key($key);

		return rawurlencode($key);
	}

}
