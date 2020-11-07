<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['::1','127.0.0.1', '*'], //只允许本地访问gii
        'generators'=> [
            'crud'=> [
                'class' => 'common\gii\generators\crud\Generator',
                'templates'=> [
                    'src'=>'@common/gii/generators/crud/default'
                ],
            ]
        ]
    ];
}

return $config;
