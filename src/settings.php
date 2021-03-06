<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database settings
        'database' => [
            'dsn' => isset($_ENV['DB_DSN']) ? $_ENV['DB_DSN'] :
                'mysql:host=localhost;dbname=fanfare;charset=utf8',
            'username' => isset($_ENV['DB_USERNAME']) ?
                $_ENV['DB_USERNAME'] : 'root',
            'password' => isset($_ENV['DB_PASSWORD']) ?
                $_ENV['DB_PASSWORD'] : 'fanfare'
        ]
    ],
];
