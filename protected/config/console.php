<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.

return CMap::mergeArray(

	require_once(dirname(__FILE__).'/main.php'),

	[

		'components'=> [

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


			'log' => [
				'class'=>'CLogRouter',
				'routes' => [
					[
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					],
					[
						'class' => 'CEmailLogRoute',
						'levels'=>'error, warning',
						'emails' => 'arti_84@mail.ru, lda3@mail.ru',
						'sentFrom' => 'noreply@xtourism',
						'subject' => 'Ошибка на сайте ' . $_SERVER['SERVER_NAME']
					],
				],
			],

		]
	]
);
