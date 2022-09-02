<?php

return new \Phalcon\Config(
    [
        'database' => [
            'adapter' => getenv('DATABASE_ADAPTER'),
            'host' => getenv('DATABASE_HOST'),
            'username' => getenv('DATABASE_USERNAME'),
            'password' => getenv('DATABASE_PASSWORD'),
            'dbname' => getenv('DATABASE_NAME'),
            'charset' => getenv('DATABASE_CHARSET'),
            'collation' => getenv('DATABASE_COLLATION')
        ],
        'application' => [
            'controllersDir' => "app/controllers/",
            'modelsDir' => "app/models/",
            'viewsDir' => APP_PATH . '/views/',
            'emailsDir' => APP_PATH . '/views/emails/',
            'logsDir' => APP_PATH . '/logs/',
            'baseUri' => "/",
            'domain' => getenv('DOMAIN')
        ],
        'mail' => [
            'noreplyEmail' => getenv('NOREPLY_EMAIL'),
            'noreplyName' => getenv('NOREPLY_NAME'),
            'supportEmail' => getenv('SUPPORT_EMAIL'),
            'supportName' => getenv('SUPPORT_NAME'),
        ],
        'pagination' => [
            'pageLimit' => 24
        ],
        'auth' => [
            'key' => getenv('JWT_KEY'),
            'accessTokenExpire' => getenv('JWT_ACCESS_TOKEN_EXPIRE'),
            'refreshTokenExpire' => getenv('JWT_REFRESH_TOKEN_EXPIRE'),
            'refreshTokenRememberExpire' => getenv('JWT_REFRESH_TOKEN_REMEMBER_EXPIRE'),
            'ignoreUri' => [
                API_VERSION . '/',
                API_VERSION . '/home',
                API_VERSION . '/users:GET',
                API_VERSION . '/users:POST',
                'regex:' . API_VERSION . '/users/[1-9][0-9]*:GET',
                'regex:' . API_VERSION . '/users/delete/[1-9][0-9]*:GET',
                'regex:' . API_VERSION . '/users/[1-9][0-9]*:PUT',
                API_VERSION . '/products:POST',
                API_VERSION . '/products:GET',
                'regex:' . API_VERSION . '/products/[1-9][0-9]*:GET',
                'regex:' . API_VERSION . '/products/delete/[1-9][0-9]*:GET',
                'regex:' . API_VERSION . '/products/[1-9][0-9]*:PUT',
                API_VERSION . '/users/id:GET',

            ]
        ],
    ]
);
