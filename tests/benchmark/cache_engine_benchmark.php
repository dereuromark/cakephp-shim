<?php

declare(strict_types=1);

use Brick\VarExporter\VarExporter;
use Cake\Cache\Cache;
use Shim\Cache\Engine\PhpEngine;

require dirname(__DIR__) . '/bootstrap.php';

if (!class_exists(VarExporter::class)) {
	fwrite(STDERR, "Missing dependency: brick/varexporter\n");
	fwrite(STDERR, "Install it with: composer require brick/varexporter\n");
	exit(1);
}

$options = getopt('', ['writes::', 'reads::']);
$writes = max(1, (int)($options['writes'] ?? 100));
$reads = max(1, (int)($options['reads'] ?? 500));

$benchmarkRoot = TMP . 'benchmarks' . DS . 'cache_engines' . DS;
ensureDir($benchmarkRoot);

$engines = [
	'File' => [
		'className' => 'File',
		'path' => $benchmarkRoot . 'file' . DS,
		'prefix' => 'bench_',
		'duration' => 0,
		'serialize' => true,
	],
	'PhpEngine' => [
		'className' => PhpEngine::class,
		'path' => $benchmarkRoot . 'php' . DS,
		'prefix' => 'bench_',
		'duration' => 0,
	],
];

$payloads = [
	'translations' => buildTranslationsPayload(),
	'model_cache' => buildModelCachePayload(),
	'nested_config' => buildNestedConfigPayload(),
	'record_list' => buildRecordListPayload(),
];

foreach ($engines as $name => $config) {
	Cache::drop($name);
	cleanDir($config['path']);
	Cache::setConfig($name, $config);
}

$results = [];
foreach ($payloads as $payloadName => $payload) {
	foreach (array_keys($engines) as $engineName) {
		Cache::clear($engineName);
		$results[] = runBenchmark($engineName, $payloadName, $payload, $writes, $reads);
	}
}

printHeader($writes, $reads);
printResults($results);

foreach (array_keys($engines) as $engineName) {
	Cache::drop($engineName);
}

/**
 * @param string $engineName
 * @param string $payloadName
 * @param mixed $payload
 * @param int $writes
 * @param int $reads
 * @return array<string, mixed>
 */
function runBenchmark(string $engineName, string $payloadName, mixed $payload, int $writes, int $reads): array {
	$writeStart = hrtime(true);
	for ($i = 0; $i < $writes; $i++) {
		Cache::write($payloadName . '_' . $i, $payload, $engineName);
	}
	$writeNs = hrtime(true) - $writeStart;

	$readStart = hrtime(true);
	for ($i = 0; $i < $reads; $i++) {
		$key = $payloadName . '_' . ($i % $writes);
		Cache::read($key, $engineName);
	}
	$readNs = hrtime(true) - $readStart;

	$payloadBytes = strlen(serialize($payload));

	return [
		'engine' => $engineName,
		'payload' => $payloadName,
		'size_kb' => round($payloadBytes / 1024, 2),
		'write_ms' => nsToMs($writeNs),
		'read_ms' => nsToMs($readNs),
		'write_us_per_op' => round(($writeNs / 1000) / $writes, 2),
		'read_us_per_op' => round(($readNs / 1000) / $reads, 2),
	];
}

/**
 * @return array<string, array<string, string>>
 */
function buildTranslationsPayload(): array {
	$payload = [];
	for ($domain = 1; $domain <= 12; $domain++) {
		$domainName = 'domain_' . $domain;
		for ($message = 1; $message <= 150; $message++) {
			$key = 'message_' . $message;
			$payload[$domainName][$key] = sprintf(
				'Translated text %d for %s with placeholder {name} and count {count}.',
				$message,
				$domainName,
			);
		}
	}

	return $payload;
}

/**
 * @return array<string, mixed>
 */
function buildModelCachePayload(): array {
	$columns = [];
	for ($i = 1; $i <= 45; $i++) {
		$name = 'field_' . $i;
		$columns[$name] = [
			'type' => match ($i % 5) {
				0 => 'integer',
				1 => 'string',
				2 => 'datetime',
				3 => 'boolean',
				default => 'decimal',
			},
			'length' => $i % 5 === 1 ? 255 : null,
			'null' => $i % 7 !== 0,
			'default' => $i % 3 === 0 ? null : 'value_' . $i,
			'comment' => 'Column comment ' . $i,
			'precision' => $i % 5 === 4 ? 10 : null,
			'scale' => $i % 5 === 4 ? 2 : null,
		];
	}

	return [
		'table' => 'articles',
		'primaryKey' => ['id'],
		'displayField' => 'title',
		'schema' => $columns,
		'indexes' => [
			'PRIMARY' => ['type' => 'primary', 'columns' => ['id']],
			'published' => ['type' => 'index', 'columns' => ['is_published', 'published']],
			'slug' => ['type' => 'unique', 'columns' => ['slug']],
		],
		'associations' => [
			'Authors' => ['type' => 'belongsTo', 'foreignKey' => 'author_id'],
			'Comments' => ['type' => 'hasMany', 'foreignKey' => 'article_id'],
			'Tags' => ['type' => 'belongsToMany', 'foreignKey' => 'article_id', 'targetForeignKey' => 'tag_id'],
		],
		'behaviors' => [
			'Timestamp' => ['events' => ['Model.beforeSave' => ['created', 'modified']]],
			'CounterCache' => ['Comments' => ['comment_count']],
		],
	];
}

/**
 * @return array<string, mixed>
 */
function buildNestedConfigPayload(): array {
	return [
		'app' => [
			'name' => 'ShimBenchmark',
			'debug' => false,
			'fullBaseUrl' => 'https://example.com',
			'paths' => [
				'plugins' => ['plugins/', 'vendor/plugins/'],
				'templates' => ['templates/', 'plugins/*/templates/'],
				'locales' => ['resources/locales/'],
			],
		],
		'routes' => array_map(
			static fn (int $i): array => [
				'template' => '/articles/' . $i . '/:slug',
				'defaults' => ['controller' => 'Articles', 'action' => 'view', 'id' => $i],
				'options' => ['_method' => ['GET'], '_ext' => ['json', 'xml']],
			],
			range(1, 250),
		),
		'featureFlags' => array_combine(
			array_map(static fn (int $i): string => 'feature_' . $i, range(1, 120)),
			array_map(static fn (int $i): bool => $i % 2 === 0, range(1, 120)),
		),
	];
}

/**
 * @return array<int, array<string, mixed>>
 */
function buildRecordListPayload(): array {
	$records = [];
	for ($i = 1; $i <= 350; $i++) {
		$records[] = [
			'id' => $i,
			'title' => 'Article ' . $i,
			'slug' => 'article-' . $i,
			'body' => str_repeat('Lorem ipsum dolor sit amet. ', 6),
			'is_published' => $i % 3 !== 0,
			'author' => [
				'id' => ($i % 20) + 1,
				'name' => 'Author ' . (($i % 20) + 1),
			],
			'tags' => ['cakephp', 'cache', 'benchmark', 'payload-' . ($i % 10)],
			'created' => '2026-05-05 10:00:00',
		];
	}

	return $records;
}

/**
 * @param array<int, array<string, mixed>> $results
 * @return void
 */
function printResults(array $results): void {
	printf(
		"%-14s %-14s %10s %12s %12s %14s %14s\n",
		'Engine',
		'Payload',
		'Size KB',
		'Write ms',
		'Read ms',
		'Write us/op',
		'Read us/op',
	);
	echo str_repeat('-', 96) . PHP_EOL;

	foreach ($results as $row) {
		printf(
			"%-14s %-14s %10.2f %12.2f %12.2f %14.2f %14.2f\n",
			$row['engine'],
			$row['payload'],
			$row['size_kb'],
			$row['write_ms'],
			$row['read_ms'],
			$row['write_us_per_op'],
			$row['read_us_per_op'],
		);
	}
}

/**
 * @param int $writes
 * @param int $reads
 * @return void
 */
function printHeader(int $writes, int $reads): void {
	echo 'Cache engine benchmark' . PHP_EOL;
	echo 'Writes per payload: ' . $writes . PHP_EOL;
	echo 'Reads per payload: ' . $reads . PHP_EOL;
	if (!filter_var(ini_get('opcache.enable_cli'), FILTER_VALIDATE_BOOL)) {
		echo 'Note: opcache.enable_cli=0, so PhpEngine read timings here do not reflect OPcache-assisted reads.' . PHP_EOL;
		echo 'Run with `php -d opcache.enable_cli=1 tests/benchmark/cache_engine_benchmark.php` for a fairer CLI comparison.' . PHP_EOL;
	}
	echo PHP_EOL;
}

/**
 * @param int $ns
 * @return float
 */
function nsToMs(int $ns): float {
	return round($ns / 1000000, 2);
}

/**
 * @param string $path
 * @return void
 */
function ensureDir(string $path): void {
	if (!is_dir($path)) {
		mkdir($path, 0777, true);
	}
}

/**
 * @param string $path
 * @return void
 */
function cleanDir(string $path): void {
	if (!is_dir($path)) {
		mkdir($path, 0777, true);

		return;
	}

	$items = glob($path . '*');
	if ($items === false) {
		return;
	}

	foreach ($items as $item) {
		if (is_dir($item)) {
			cleanDir($item . DS);
			rmdir($item);

			continue;
		}

		unlink($item);
	}
}
