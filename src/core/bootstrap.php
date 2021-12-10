<?php
use ProyectoWeb\core\App;
use ProyectoWeb\database\Connection;
use ProyectoWeb\app\utils\MyLog;

$config = require_once __DIR__ . '/../app/config.php';
App::bind('config', $config);
App::bind('connection', Connection::make(App::get('config')['database']));


App::bind("logger", MyLog::load($config['logs']['channel'], __DIR__ ."/../logs/" . $config['logs']['filename'],  $config['logs']['level']));
