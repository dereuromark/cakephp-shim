<?php
// For controller pagination
Configure::write('Paginator.paramType', 'querystring');

// For CakeDC search plugin
Configure::write('Search', array(
		'Prg' => array(
			'commonProcess' => array('paramType' => 'querystring', 'filterEmpty' => true),
			'presetForm' => array('paramType' => 'querystring')
		)
));

// Shims
// Add them in your own bootstrap.