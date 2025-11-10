<?php
defined('B_PROLOG_INCLUDED') || die();

// Автозагрузка классов
\Bitrix\Main\Loader::registerAutoLoadClasses(
    'your.module',
    [
        // Массив для автозагрузки: 'Класс' => 'путь/к/файлу.php'
        'Your\Module\ExampleTable' => 'lib/orm/exampletable.php',
        'Your\Module\SomeClass' => 'lib/someclass.php',
    ]
);