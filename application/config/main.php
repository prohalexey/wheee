<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'application\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'container' => [
        'definitions' => [
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-application',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'wheeeapp',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ]
    ],
    'params' => $params,
];
