<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

Yii::setPathOfAlias('TSearch', realpath(__DIR__ . '/../lib/tsearch/'));

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'DiAr System',
    'language'  => 'ru',
    'charset'=>'UTF-8',
    'sourceLanguage' => 'ru',
    'timeZone' => 'UTC',

    // Сжатие gzip
    'onBeginRequest' => function($event){ return ob_start("ob_gzhandler"); },
    'onEndRequest' => function($event){ return ob_end_flush(); },

    // используемые приложением поведения
    'behaviors'=>array(
        'runEnd'=>array(
            'class'=>'application.behaviors.WebApplicationEndBehavior',
        ),
    ),

    // preloading 'log' component
	'preload'=>array('fatalerrorcatch', 'log'),

    'aliases' => array(
        'widgets' => 'application.widgets', // change this if necessary
        'tsearch' => 'application.widgets.tsearch', // change this if necessary
        'common_views' => 'application.views.common', // change this if necessary
        'actions' => 'application.controllers.actions', // change this if necessary
    ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
        'application.models.AR.*',
        'application.models.AR.tour_showcase.*',
        'application.models.AR.searcher.*',
        'application.components.*',
        'application.lib.*',
        'application.lib.tsearch.*',
        'application.lib.tsearch.dictionaries.directories.*',
        'application.lib.tsearch.drivers.*',
        'application.widgets.menu.*',

    ),

//	'modules'=>array(
//		// uncomment the following to enable the Gii tool
//
//		'gii'=>array(
//			'class'=>'system.gii.GiiModule',
//			'password'=>'1111111',
//			// If removed, Gii defaults to localhost only. Edit carefully to taste.
//			'ipFilters'=>array('127.0.0.1','::1'),
//		),
//
//	),

	// application components
	'components'=>array(

		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),



//        'errorHandler'=>array(
//			// use 'site/error' action to display errors
//			'errorAction'=>'site/error',
//		),

        'widgetFactory' => array(

            'widgets' => array(
                'CGridView' => array(
                    'beforeAjaxUpdate' => 'function(){$.showFade();}',
                    'afterAjaxUpdate' => 'function(){$.hideFade();}',
                    'loadingCssClass' => '',
                ),

                'CJuiDatePicker' => array(
                    'language' => 'ru'
                )

            )
        ),


    ),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
        'adminEmail'=>'arti_84@mail.ru',
        'shopEmail'=>'lda3@mail.ru',
        'encrypted_salt' => '#!zQwE%&?@gGd((]',
        'hotel_id_prefix' => '#@!g3$^',

        'lpBuilderHost' => (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on') ? 'https://' : 'http://') . 'lpbuilder.localhost'
//        'lpBuilderHost' => (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on') ? 'https://' : 'http://') . 'construct.diar.tech'
	),
);
