<?php 
return [
    'components' => [
        // 'db' => [
        //     'class' => 'etc\components\Pgsql',
        //     'dsn' => '',
        //     'username' => '',
        //     'password' => '',
        //     'charset' => 'utf8',
        // ],
        'ui' => [
            'class' => 'etc\components\Shit',
        ],
        'auth' => [
            'class' => 'etc\components\AuthManager',
            'login_page' => '/login',
            'default_role' => 'guest',
        ],
        'mailer' => [
            'class' => 'etc\components\SwiftMailer',
            'smtp' => '',
            'login' => '',
            'password' => '',
            'port' => 587,
            'encryption' => 'tls',
        ],
        'urlManager' => [
            'base_url' => 'http://site.local',
            'class' => 'etc\components\UrlManager',
        ]
    ],
    'debug' => true,
];