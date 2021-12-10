<?php
return [
    'database' => [
        'name' => 'proyecto1',
        'username' => 'root',
        'password' => 'sa',
        'connection' => 'mysql:host=localhost',
        'options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND=> 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ]
    ],
    'logs' =>[
        'channel' => 'principal',
        'filename' => 'proyectoWeb.log',
        'level' => \Monolog\Logger::INFO
    ]
];