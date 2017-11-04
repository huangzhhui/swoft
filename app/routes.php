<?php
use App\Controllers\IndexController;

/* @var \Swoft\Web\Router $router */
$router = \Swoft\App::getBean('router');

$router->any('/', IndexController::class);
