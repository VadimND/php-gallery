<?php
require './vendor/autoload.php';
use App\Db;
use App\User;
use App\UserController;
use App\UserRepository;

try {
    $controller = (new \App\UserController(
        new \App\UserRepository(
            new \App\Db()
        )
    ));
    echo $controller->handle();
} catch (Throwable $exception) {
    echo 'Ошибка: ' . $exception->getMessage();
}