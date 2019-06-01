<?php

return CMap::mergeArray(

    require_once(dirname(__FILE__).'/main.php'),

    array(

        // стандартный контроллер
        'defaultController' => 'Welcome',

        //'timeZone' => 'UTC',

        'components'=>array(

            'user'=>array(
                'class' => 'WebUser',
                // enable cookie-based authentication
                'allowAutoLogin'=>true,
                'loginUrl' => array('Welcome/login'),
            ),

            'authManager' => array(
                // Будем использовать свой менеджер авторизации
                'class' => 'PhpAuthManager',
                // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
                'defaultRoles' => array('guest'),
            ),

            'simpleImage' => array(
                'class' => 'application.extensions.simpleimage.CSimpleImage',
            ),

            'clientScript' => array(

//                'class' => 'application.extensions.EClientScript.EClientScript',
//                'combineScriptFiles' => ! YII_DEBUG, // По умолчанию это значение равно true. Установите в false, если не хотите склеивать javascript файлы
//                'combineCssFiles' => ! YII_DEBUG, // То же, но для CSS файлов
//                'optimizeScriptFiles' => ! YII_DEBUG, // Минифицировать JS файлы
//                'optimizeCssFiles' => ! YII_DEBUG, // Минифицировать CSS файлы
//                'optimizeInlineScript' => false, // Минифицировать JS внутри страниц. Это может замедлить загрузку
//                'optimizeInlineCss' => false, // То же, но для CSS внутри страниц – в секции <head></head>

                'scriptMap' => array(
                    'jquery.js' => 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.js',
                    'jquery.min.js' => 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js',
                    'jquery-ui.min.js' => 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
                ),

                'packages' => array(

                    'bootstrap3' => array(
                        'baseUrl' => 'js/bootstrap3',
                        'js' => array(YII_DEBUG ? 'bootstrap.js' : 'bootstrap.js'),
                        // Зависимость от другого пакета
                        'depends'=>array('jquery'),
                    ),

                    'common' => array(
                        'baseUrl' => 'js/',
                        'js' => array(YII_DEBUG ? 'common.js' : 'common.js'),
                        // Зависимость от другого пакета
                        'depends'=>array('jquery'),
                    ),

                    'ckeditor' => array(
                        'baseUrl' => 'js/plugins/ckeditor_base/',
                        'js' => array('ckeditor.js'),
                        // Зависимость от другого пакета
                        'depends'=>array('jquery'),
                    ),

                    'slick' => array(
                        'baseUrl' => 'js/plugins/slick/',
                        'js' => array( YII_DEBUG ? 'slick.js' : 'slick.min.js'),
                        // Зависимость от другого пакета
                        'depends'=>array('jquery'),
                    ),
                ),
            ),

            'db'=>array(

                'connectionString' => 'mysql:host=127.0.0.1;dbname=xtourism',
                'username' => 'root',
                'password' => '',

//                'connectionString' => 'mysql:host=localhost;dbname=baptistz_xtourism',
//                'username' => 'baptistz_diartech',
//                'password' => 'ay#];P{w*z95',

                'emulatePrepare' => true,
                'charset' => 'utf8',
                'tablePrefix' => 'xt_',
                'enableProfiling' => true
            ),

            'fatalerrorcatch' => array(
                'class'=>'ext.error.FatalErrorCatch',
            ),

            'errorHandler'=>array(
                'errorAction'=>'Welcome/error',
            ),

            'log'=>array(
                'class'=>'CLogRouter',
                'routes' => array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                    ),
                    array(
                        'class' => 'CEmailLogRoute',
                        'levels'=>'error, warning',
                        'emails' => 'arti_84@mail.ru, lda3@mail.ru',
                        'sentFrom' => 'noreply@xtourism',
                        'subject' => 'Ошибка на сайте ' . $_SERVER['SERVER_NAME']
                    ),

                    // uncomment the following to show log messages on web pages
                    /*
                    array(
                        'class'=>'CWebLogRoute',
                    ),
                    */
                ),
            ),

            'widgetFactory' => array(
                'widgets' => array(
                    'CBreadcrumbs' => array(
                        //'tagName' => 'ul',
                        'homeLink' => true,
                        'htmlOptions' => array('class' => 'breadcrumb'),
                        'separator' => '&nbsp;&nbsp;&nbsp;&raquo;&nbsp;&nbsp;&nbsp;',
                        'inactiveLinkTemplate' => '<span class="inactive">{label}</span>',
                        //'activeLinkTemplate' => '<li class="active"><a href="{url}">{label}<a/></li>',
                        'encodeLabel' => false
                    ),

                    'CLinkPager' => array(
                        'header' => '',
                        'htmlOptions' => array(
                            'class' => 'pagination pagination-sm'
                        ),
                        'selectedPageCssClass' => 'active',
//                        'firstPageCssClass' => 'hidden',
//                        'lastPageCssClass' => 'hidden',
                    ),

                    'CJuiDatePicker' => array(

                        'htmlOptions'=>array(
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            'style' => 'cursor: default;'
                        ),

                        'options' => array(
                            'showOtherMonths' => true,
                            'changeMonth' => true,
                            'changeYear' => true,
                            'showButtonPanel'=>true,
                            'dateFormat' => 'dd.mm.yy',
                            'showAnim' => 'fadeIn',
                            'beforeShow' => "js:function() {
                                $('.ui-datepicker').css('font-size', '0.9em');
                                setTimeout(function(){ $('.ui-datepicker').css('z-index', 1000) }, 100);
                            }"
                        )
                    )

                )
            ),

        )
    )
);