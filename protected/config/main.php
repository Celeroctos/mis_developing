<?php

$siteName = '';
define('_STATIC', 'static_src'); //папка с ресурсами (для дальнейших публикаций). См. CAssetManager

// Вот за такое надо расстреливать на месте. Но автор сего шедевра не знает как сделать что-то более правильное
//          относительно быстро (( а времени нет((
// Если в строке есть "moniiag" - значит мы на тестовом
if (strpos($_SERVER['HTTP_HOST'], 'moniiag') !== false && strpos($_SERVER['HTTP_HOST'], 'moniiag') >= 0) {
    $siteName = 'МИС Notum Тестовый сервер';
} else {
    $siteName = 'МИС Notum';
}

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>$siteName,
	'defaultController' => 'index',
	'layout' => 'index',
	'language' => 'ru',
	'sourceLanguage'=>'en_us',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
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

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Cvbhyjd1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		 
        'reception' => array(
            'class' => 'application.modules.reception.ReceptionModule',
            'import'=>array(
                'application.modules.reception.models.*',
                'application.modules.reception.components.*',
                'application.modules.reception.controllers.*'
            ),
        ),
        'guides' => array(
            'class' => 'application.modules.guides.GuidesModule',
            'import'=>array(
                'application.modules.guides.models.*',
				'application.modules.guides.components.widgets.*',
                'application.modules.guides.components.*',
                'application.modules.guides.controllers.*',
            ),
        ),
        'admin' => array(
            'class' => 'application.modules.admin.AdminModule',
            'import'=>array(
                'application.modules.admin.models.*',
                'application.modules.admin.components.*',
                'application.modules.admin.controllers.*'
            ),
        ),
        'doctors' => array(
            'class' => 'application.modules.doctors.DoctorsModule',
            'import'=>array(
                'application.modules.doctors.models.*',
                'application.modules.doctors.components.*',
                'application.modules.doctors.controllers.*'.
				# import components for doctor module
				'application.modules.laboratory.widgets.*',
				'application.modules.laboratory.forms.*',
				'application.modules.laboratory.models.*',
                # import medcard components
                'application.modules.medcard.components.*',
                'application.modules.medcard.controllers.*',
                'application.modules.medcard.models.*',
                'application.modules.medcard.forms.*',
                'application.modules.medcard.widgets.*',
                'application.modules.medcard.grids.*',
            ),
        ),
        'settings' => array(
            'class' => 'application.modules.settings.SettingsModule',
            'import'=>array(
                'application.modules.settings.models.*',
                'application.modules.settings.components.*',
                'application.modules.settings.controllers.*'
            ),
        ),
        'statistic' => array(
            'class' => 'application.modules.statistic.StatisticModule',
            'import'=>array(
                'application.modules.statistic.models.*',
                'application.modules.statistic.components.*',
                'application.modules.statistic.controllers.*'
            ),
        ),
		'hospital' => array(
            'class' => 'application.modules.hospital.HospitalModule',
            'import'=>array(
                'application.modules.hospital.models.ar.*',
				'application.modules.hospital.models.forms.*',
                'application.modules.hospital.components.*',
                'application.modules.hospital.controllers.*'
            ),
        ),
        'laboratory' => array(
            'class' => 'application.modules.laboratory.LaboratoryModule2',
            'import' => array(
                'application.modules.laboratory.components.*',
                'application.modules.laboratory.controllers.*',
                'application.modules.laboratory.models.*',
                'application.modules.laboratory.forms.*',
                'application.modules.laboratory.widgets.*',
				'application.modules.laboratory.grids.*',
				'application.vendor.barcodegen.*',
				'application.vendor.barcodegen.drawer.*',
                'application.modules.laboratory.modals.*',
            )
        ),
		'medcard' => array(
			'class' => 'application.modules.medcard.MedcardModule',
			'import' => array(
				'application.modules.medcard.components.*',
				'application.modules.medcard.controllers.*',
				'application.modules.medcard.models.*',
				'application.modules.medcard.forms.*',
				'application.modules.medcard.widgets.*',
				'application.modules.medcard.grids.*',

			)
		)
	),

	// application components
	'components'=>array(
        'request' => array(
            //'enableCsrfValidation' => true,
            //'enableCookieValidation' => true,
        ),
		'widgetFactory' => [
			'widgets' => [
				'CBreadcrumbs' => [
					'tagName' => 'ol',
					'separator' => '',
					'homeLink' => false,
					'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
					'inactiveLinkTemplate' => '<li>{label}</li>',
					'htmlOptions' => [
						'class' => 'breadcrumb'
					]
				],
				'CGridView' => [
					'cssFile' => false,
					'itemsCssClass' => 'table table-hover table-striped table-bordered',
					'pagerCssClass' => 'pagerCssClass',
				],
				'CLinkPager' => [
					'cssFile' => false,
					'htmlOptions' => [
						'class' => 'pagination pagination-sm'
					],
					'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
					'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
					'firstPageLabel' => '<span class="glyphicon glyphicon-fast-backward"></span>',
					'lastPageLabel' => '<span class="glyphicon glyphicon-fast-forward"></span>',
					'hiddenPageCssClass' => 'disabled',
					'header' => '',
					'selectedPageCssClass' => 'active',
				],
				'LinkPager' => [
					'cssFile' => false,
					'htmlOptions' => [
						'class' => 'pagination'
					],
					'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
					'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
					'firstPageLabel' => '<span class="glyphicon glyphicon-fast-backward"></span>',
					'lastPageLabel' => '<span class="glyphicon glyphicon-fast-forward"></span>',
					'hiddenPageCssClass' => 'disabled',
					'header' => '',
					'selectedPageCssClass' => 'active',
				],
				'CButtonColumn' => [
					'deleteConfirmation' => false,
					'deleteButtonUrl' => false
				]
			],
		],
        'clientScript' => array(
            'scriptMap' => array(
                'jquery.js' => '/js/libs/jquery-1.10.2.min.js'
            ),
			'packages'=>[
				'datetimepicker' => [
					'baseUrl' => _STATIC . '/datetimepicker/',
					'js' => [
						'js/jquery.datetimepicker.js'
					],
					'css' => [
						'css/jquery.datetimepicker.css'
					],
					'depends' => [
						'jquery'
					]
				],
			]
        ),
		'user'=>array(
			// enable cookie-based authentication
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
//			'rules'=>array(
//				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
//				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
//				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
//			),
		),
		'db'=> include( dirname(__FILE__).'/db/db.php'),
		// uncomment the following to use a MySQL database
		'db2' => array(
			'class'=>'system.db.CDbConnection',
				'connectionString' => 'sqlsrv:Server=7.79.78.102\HISSQLEE,1433;Database=PDPStdStorage',
				'username' => 'sa',
				'password' => 'system54@nof',
				'charset' => 'utf8'
			),
		
		'db3' => array(
			'class'=>'system.db.CDbConnection',
				    'connectionString' => 'sqlsrv:Server=7.79.78.102\HISSQLEE,1433;Database=PDPRegStorage',
				    'username' => 'sa',
				    'password' => 'system54@nof',
				    'charset' => 'utf8'
			    ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'index/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
//					'levels'=>'error, warning, trace, profile, info',
					'levels'=>'error, warning, info',
				),
//                array(
//                    'class' => 'CWebLogRoute',
//                    'categories' => 'application',
//                    'levels'=>'error, warning, trace, profile, info',
//                    'showInFireBug' => true
//                ),
			),
		),
		'ePdf' => array(
			'class'         => 'ext.yii-pdf-0_3_2.EYiiPdf',
			'params'        => array(
			    'mpdf'     => array(
				'librarySourcePath' => 'application.vendor.mpdf6.*',
				'constants'         => array(
				    '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
				),
				'class'=>'mpdf'/*, // the literal class filename to be loaded from the vendors folder
				'defaultParams'     => array( // More info: http://mpdf1.com/manual?tid=184
				    'mode'              => '', //  This parameter specifies the mode of the new document.
				    'format'            => 'A4', // format A4, A5, ...
				   // 'default_font_size' => 0, // Sets the default document font size in points (pt)
				    'default_font'      => '', // Sets the default font-family for the new document.
				    'mgl'               => 15, // margin_left. Sets the page margins for the new document.
				    'mgr'               => 15, // margin_right
				    'mgt'               => 16, // margin_top
				    'mgb'               => 16, // margin_bottom
				    'mgh'               => 9, // margin_header
				    'mgf'               => 9, // margin_footer
				 //   'orientation'       => 'P', // landscape or portrait orientation
		),
				)*/
	    /*
				'defaultParams'     => array( // More info: http://mpdf1.com/manual?tid=184
				 'autoPageBreak' => true
				)
	    */
			    ),
			    'HTML2PDF' => array(
				'librarySourcePath' => 'application.vendor.html2pdf_v4_03.*',
				'classFile'         => 'html2pdf.class.php', // For adding to Yii::$classMap
				/*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
				    'orientation' => 'P', // landscape or portrait orientation
				    'format'      => 'A4', // format A4, A5, ...
				    'language'    => 'en', // language: fr, en, it ...
				    'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
				    'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
				    'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
				)*/
			    )
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);