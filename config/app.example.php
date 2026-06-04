<?php

/**
 * Shim Example Configuration
 *
 * Merge the keys below into your application's config/app.php (or
 * config/app_local.php) — do not replace the whole file, since this snippet
 * only contains this plugin's configuration. When copying entries that
 * reference imported classes, use fully-qualified class names or move the
 * `use` imports to the top of the target file. Customize the values as needed.
 */
return [
	'Shim' => [
		// Deprecation triggering. Set to bool `true` to globally enable triggering of
		// deprecation warnings raised via Shim\Deprecations::error(). Default: not set
		// (treated as disabled). You can also use a per-type array to selectively
		// enable/disable specific deprecation groups; a `true`/`false` for a specific
		// type takes precedence over the global flag.
		'deprecations' => false,
		// Example of per-type toggling (each value must be a bool):
		// 'deprecations' => [
		//     'methodSignature' => true,  // Enable this group
		//     'configKey' => false,       // Disable this group even if global is on
		// ],

		// Error level used by Shim\Deprecations::error() when triggering messages.
		// Must be one of the trigger_error() user levels: E_USER_NOTICE, E_USER_WARNING,
		// E_USER_DEPRECATED, E_USER_ERROR. Any invalid value falls back to E_USER_DEPRECATED.
		// Default: not set (E_USER_DEPRECATED).
		'deprecationType' => E_USER_DEPRECATED,

		// When true, Shim\Controller\Controller::afterFilter() checks whether headers were
		// already sent before the response is emitted (skipped for the Error controller and
		// in CLI). In debug mode this throws an Exception; otherwise it triggers an error.
		// Helps catch stray output/whitespace in controllers. Default: not set (disabled).
		'monitorHeaders' => false,
	],

	// This key is owned by dereuromark/cakephp-ide-helper (see that plugin's
	// config/app.example.php for the full set of options). Shim's EntityAnnotator
	// honors it too: when enabled (true, or 'detailed' for fully detailed types),
	// generated entity setter methods keep the iterable value type of array/json
	// fields, e.g. setTagsOrFail(array<\App\Model\Entity\Tag> $value) instead of a
	// bare setTagsOrFail(array $value). Without it the value type collapses to the
	// base type. Default: false.
	'IdeHelper' => [
		'genericsInParam' => false,
	],
];
