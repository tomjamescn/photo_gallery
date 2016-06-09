<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '9kIR8n-GpOXuU8f8aDF1pmeVvkBo7x_N',
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

        'log' => [

            'flushInterval' => 1,

            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'exportInterval' => 1,
                    'levels' => ['error', 'warning', 'profile', 'trace', 'info'],
                    'logVars' => [],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,

    'on beforeRequest' => function($event) {
        //处理请求之前,将每个请求都会用的数据在这里处理
        //比如导航中需要的数据
        $tagList = \app\models\Tag::find()->all();
        $tagItems = [];
        $count = 0;
        $tagListShowMax = 10;
        foreach ($tagList as $tag) {
            if($count == $tagListShowMax) {
                break;
            }
            $count++;
            $item = [
                'label' => $tag->tagName,
                'url' => '?r=site&tagId='.$tag->id,
            ];
            $tagItems[] = $item;
        }

        if($count >= $tagListShowMax) {
            $tagItems[] = [
                'label' => '',
                'url' => '',
                'options' => [
                    'class' => 'divider',
                ],
            ];
            $tagItems[] = [
                'label' => '全部标签',
                'url' => '?r=tag/index',
            ];
        }

        Yii::$app->params['tagItems'] = $tagItems;

        Yii::trace('on beforeRequest');
    },
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
