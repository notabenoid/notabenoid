<?php

return CMap::mergeArray(
    require(__DIR__.'/main.php'),
    [
        'catchAllRequest' => null,
        'components' => [
            'log' => [
                'class' => 'CLogRouter',
                'routes' => [
                    ['class' => 'CFileLogRoute', 'levels' => 'error, warning'],
                    [
                        'class' => 'CWebLogRoute',
                        'categories' => 'application.*, system.db.CDbCommand, ext.yii-mail.YiiMail',
                    ],
                    ['class' => 'CProfileLogRoute'],
                ],
            ],
            'db' => [
                'enableParamLogging' => true,
            ],

            'mail' => [
                'logging' => true,
                'dryRun' => true,
            ],
        ],
        'params' => [
            'domain' => 'notabenoid.dev.romakhin.ru',
            'ENVIRONMENT' => 'dev',
        ],
    ]
);
