<?php
require './vendor/autoload.php';
use App\Container;

try {
    $controller = (new \App\Container())->get(\App\UserController::class);
    echo $controller->handle();
} catch (Throwable $exception) {
    echo 'Ошибка: ' . $exception->getMessage();
}
