<?php

use Bitrix\Main\Loader;

Loader::includeModule('custom.catalog');

// Дополнительная бизнес-логика проекта подключается здесь
require_once __DIR__ . '/include/legacy_discount.php';