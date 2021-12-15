<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/core/bootstrap.php';

use ProyectoWeb\app\controllers\AsociadosController;
use Slim\Views\PhpRenderer;
use ProyectoWeb\app\controllers\PageController;
use ProyectoWeb\app\controllers\ContactController;
use ProyectoWeb\app\controllers\UserController;
use ProyectoWeb\app\controllers\GaleriaController;
use ProyectoWeb\core\App;


App::bind('rootDir', __DIR__ . '/');

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ],
];
$app = new \Slim\App($config);

$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("../src/app/views");

$app->get('/', PageController::class . ':home')->setName("home");
$app->get('/about', PageController::class . ':about');
$app->get('/blog', PageController::class . ':blog');
$app->get('/single_post', PageController::class . ':singlePost');
$app->map(['GET', 'POST'], '/contact', ContactController::class . ':contact');
$app->map(['GET', 'POST'], '/login', UserController::class . ':login');
$app->map(['GET', 'POST'], '/register', UserController::class . ':register');
$app->map(['GET', 'POST'], '/asociados', AsociadosController::class . ':asociados');
$app->map(['GET', 'POST'], '/galeria', GaleriaController::class . ':galeria');
$app->get('/logout', UserController::class . ':logout');

$app->run();