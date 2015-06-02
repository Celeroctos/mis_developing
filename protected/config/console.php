<?php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Console Application',
	// preloading 'log' component
	'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
		'application.components.*',
		'application.controllers.*',
		'application.assets.*',
		'application.widgets.*',
		'application.forms.*',
		'application.fields.*',
		'application.validators.*'
	),
	// application components
	'components'=>array(
		'db'=>include( dirname(__FILE__).'/db/db.php' ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);