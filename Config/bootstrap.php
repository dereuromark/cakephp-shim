<?php
// For controller pagination
Configure::write('Paginator.paramType', 'querystring');

// For CakeDC search plugin
Configure::write('Search', [
		'Prg' => [
			'commonProcess' => ['paramType' => 'querystring', 'filterEmpty' => true],
			'presetForm' => ['paramType' => 'querystring']
		]
]);

// Shims
// Add them in your own bootstrap.