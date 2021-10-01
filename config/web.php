<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dbart = require __DIR__ . '/dbart.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'artlist' => [
            'class' => 'app\modules\artlist\Module',
        ],
    ],

    'components' => [
        'db' => $db,
        'dbart' => $dbart,
       /* 'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['admin@artlist.pro' => 'ARTLIST.PRO'],
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',
                'username' => 'antogon10024',
                'password' => 'breackpoints238',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],*/

		'request' => [
            'cookieValidationKey' => 'bMM7ekI1ECC8KBgYtDGstlIj9rQ3SJT9',
			'baseUrl'=> '',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'artlist/<city_name:>' => 'artlist/default/index',
            ],
        ],

        'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
                // uncomment this to use streams in safe_mode
                //'useStreamsFallback' => true,
            ],
            'services' => [ // You can change the providers and their classes.

                //'facebook' => [
                //    // register your app here: https://developers.facebook.com/apps/
                //    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                //    'clientId' => '2087673024864793',
                //    'clientSecret' => '5b5547e71079e5bf54f2b63ab99bae35',
                //],
                'google' => [
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'clientId' => '682672245933-t5tkjruiieog3dea8dvmfqabb56hotgh.apps.googleusercontent.com',
                    'clientSecret' => 'DJKxbGYYbqHTM_MRLmWmJKX6',
                    'title' => 'Google',
                ],

                'vkontakte' => [
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
                    'clientId' => '123',
                    'clientSecret' => 'QouVqsrnrqZMBQhtYpye',
                ],
                'odnoklassniki' => [
                    // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
                    // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                    'class' => 'app\components\OdnoklassnikiOAuth2Service',
                    'clientId' => '123',
                    'clientSecret' => '123',
                    'clientPublic' => '123',
                    'title' => 'Odnoklas.',
                ]
            ],
        ],

        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],
    ],
    'params' => $params,
];


if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

