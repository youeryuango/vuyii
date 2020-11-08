<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-src',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'src\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-src',
        ],
        'user' => [
            'identityClass' => 'common\models\user\UserAdmin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-src', 'httpOnly' => true],
            'enableSession' => false
        ],
        'session' => [
            // this is the name of the session cookie used for login on the src
            'name' => 'advanced-src',
        ],
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key' => '123456',
            // You have to configure ValidationData informing all claims you want to validate the token.
            'jwtValidationData' =>\src\components\JwtValidationData::class,
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
        ],
    ],
    'params' => $params,
];
