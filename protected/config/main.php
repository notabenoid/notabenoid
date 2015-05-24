<?php

return [
    'basePath' => __DIR__.'/..',
    'name' => 'Notabenoid.Org',
    'language' => 'ru',
    'sourceLanguage' => 'en',

    'preload' => ['log', 'bootstrap'],

    'import' => [
        'application.models.*',
        'application.components.*',
        'ext.yii-mail.YiiMailMessage',
    ],

    'modules' => [
    ],

    'components' => [
        'request' => [
            'enableCookieValidation' => true,
        ],
        'urlManager' => [
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => [
                'users/<id:\d+>' => 'users/books',
                'users/<id:\d+>/<action:\w+>' => 'users/<action>',
                'users/<id:\d+>/translations/<book_id:\d+>' => 'users/translations',

                'book/<book_id:\d+>/blog' => 'bookBlog/index',
                'book/<book_id:\d+>/blog/<post_id:\d+>' => 'bookBlog/post',
                'book/<book_id:\d+>/blog/<post_id:\d+>/c<comment_id:\d+>/<action>' => 'bookBlog/comment_<action>',
                'book/<book_id:\d+>/blog/<post_id:\d+>/<action:\w+>' => 'bookBlog/<action>',
                'book/<book_id:\d+>/blog/edit' => 'bookBlog/edit',

                'book/<book_id:\d+>/announces' => 'announces/book',
                'book/<book_id:\d+>/announces/<post_id:\d+>' => 'announces/post',
                'book/<book_id:\d+>/announces/<post_id:\d+>/c<comment_id:\d+>/<action>' => 'announces/comment_<action>',
                'book/<book_id:\d+>/announces/<post_id:\d+>/<action:\w+>' => 'announces/<action>',
                'book/<book_id:\d+>/announces/write' => 'announces/edit',

                'book/<book_id:\d+>/edit' => 'bookEdit/index',
                'book/<book_id:\d+>/edit/<action:\w+>' => 'bookEdit/<action>',

                'book/<book_id:\d+>' => 'book/index',
                'book/<book_id:\d+>/<chap_id:\d+>' => 'chapter/index',
                'book/<book_id:\d+>/<chap_id:\d+>/<orig_id:\d+>' => 'orig/index',
                'book/<book_id:\d+>/<chap_id:\d+>/<orig_id:\d+>/c<comment_id:\d+>/<action:\w+>' => 'orig/comment_<action>',
                'book/<book_id:\d+>/<chap_id:\d+>/<orig_id:\d+>/<action:\w+>' => 'orig/<action>',
                'book/<book_id:\d+>/<chap_id:\d+>/<action:\w+>' => 'chapter/<action>',
                'book/<book_id:\d+>/<action:\w+>' => 'book/<action>',

                'blog/<post_id:\d+>' => 'blog/post',
                'blog/<post_id:\d+>/c<comment_id:\d+>/<action>' => 'blog/comment_<action>',
                'blog/<post_id:\d+>/<action:\w+>' => 'blog/<action>',

                'chat/room/<room_id:\d+>' => 'chat/room',

                'my/comments' => 'myComments/index',
                'my/comments/<action:\w+>' => 'myComments/<action>',

                'my/bookmarks' => 'Bookmarks/index',
                'my/bookmarks/<action:\w+>' => 'Bookmarks/<action>',

                'my/mail/' => 'mail/index',
                'my/mail/<id:\d+>' => 'mail/message',
                'my/mail/<action:\w+>' => 'mail/<action>',

                'catalog/<cat_id:\d+>' => 'catalog/index',

                'site/login' => 'register/login',
            ],
        ],
        'db' => require(__DIR__.'/db.php'),
        'session' => [
            'class' => 'CHttpSession',
            'cookieParams' => [
                'lifetime' => 86400 * 365,
            ],
            'timeout' => 86400 * 365,
        ],
        'cache' => [
            'class' => 'system.caching.CMemCache',
            'servers' => [
                ['host' => 'localhost', 'port' => 11211, 'weight' => 100],
            ],
            'keyPrefix' => 'nb',
        ],
        'readycache' => [
            'class' => 'application.components.ReadyCache',
            'directoryLevel' => '3',
            'gCProbability' => 0,    // garbage collection - только вручную, ну его нахуй
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                ['class' => 'CFileLogRoute', 'levels' => 'error, warning'],
            ],
        ],
        'widgetFactory' => [
            'widgets' => [
                'CActiveForm' => [
                ],
                'TbPager' => [
                    'maxButtonCount' => 20,
                    'header' => "<div class='pagination'>",
                    'footer' => '</div>',
                    'displayFirstAndLast' => true,
                    'firstPageLabel' => '&laquo;&laquo;&laquo;',
                    'lastPageLabel' => '&raquo;&raquo;&raquo;',
                    'nextPageLabel' => '&raquo;',
                    'prevPageLabel' => '&laquo;',
                ],
                'CLinkPager' => [
                    'maxButtonCount' => 20,
                    'cssFile' => '/css/pager.css',
                    'header' => false,
                    'firstPageLabel' => '&laquo;&laquo;&laquo;',
                    'lastPageLabel' => '&raquo;&raquo;&raquo;',
                    'nextPageLabel' => '&raquo;',
                    'prevPageLabel' => '&laquo;',
                ],
                'CGridView' => [
                    'template' => "{pager}\n{items}\n{pager}",
                    'cssFile' => '/css/grid.css',
                    'rowCssClass' => '',
                    'selectableRows' => 0,
                ],
                'CHtmlPurifier' => [
                    'options' => [
                        'HTML.Allowed' => 'a[href],b,strong,i,em,u',
                    ],
                ],
            ],
        ],

        /*
        * 3rd party-компоненты
        */
        'mail' => [
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.email',
        ],
        'bootstrap' => [
            'class' => 'ext.bootstrap.components.Bootstrap',
            'coreCss' => true,
            'responsiveCss' => true,
            'plugins' => [
                'transition' => false, // disable CSS transitions
                'tooltip' => [
                    'selector' => 'a.tooltip', // bind the plugin tooltip to anchor tags with the 'tooltip' class
                    'options' => [
                        'placement' => 'bottom', // place the tooltips below instead
                    ],
                ],
            ],
        ],
        'curl' => [
            'class' => 'application.extensions.curl.Curl',
            'options' => [
                'timeout' => 30,
                'setOptions' => [
                    CURLOPT_USERAGENT => 'Notabenoid.Org Translation Service (support@notabenoid.org)',
                    CURLOPT_RANGE => '0-2048000',   // Качаем не более 2 мегов
                    CURLOPT_TIMEOUT => 15,
                ],
            ],
        ],
        'filecache' => [
            'class' => 'system.caching.CFileCache',
        ],

        /*
        * Мои компоненты
        */
        'user' => [
            'class' => 'application.components.WebUser',
            'allowAutoLogin' => true,
            'autoRenewCookie' => true,
        ],
        'langs' => [
            'class' => 'application.components.Langs',
        ],
        'parser' => [
            'class' => 'application.components.Parser',
        ],
    ],

    'params' => require(__DIR__.'/params.php'),
];
