<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return [
    'basePath' => __DIR__.'/..',
    'name' => 'Notabenoid.Org',
    'language' => 'ru',
    'sourceLanguage' => 'en',

    'import' => [
        'application.models.*',
        'application.components.*',
        'ext.yii-mail.YiiMailMessage',
    ],

    'components' => [
        'db' => [
            'connectionString' => 'pgsql:host=localhost;dbname=notabenoid',
            'username' => 'notabenoid',
            'password' => '',
            'charset' => 'utf8',

            'emulatePrepare' => true,
            'schemaCachingDuration' => 60 * 30,
            'enableProfiling' => true,
        ],
        'mail' => [
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.email',
            'logging' => false,
            'dryRun' => false,
        ],
        'langs' => [
            'class' => 'application.components.Langs',
        ],
        'parser' => [
            'class' => 'application.components.Parser',
        ],
    ],

    'params' => [
        'domain' => 'notabenoid.org',
        'adminEmail' => 'support@notabenoid.org',
        'commentEmail' => 'comment@notabenoid.org',
        'systemEmail' => 'no-reply@notabenoid.org',
    ],
];
