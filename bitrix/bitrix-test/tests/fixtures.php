<?php

/**
 * In-memory fixtures, зеркалирующие database/seed.sql.
 * Используются эмуляцией Bitrix API в bootstrap.php.
 */

$GLOBALS['__DB']['elements'] = [
    100 => ['ID' => 100, 'IBLOCK_ID' => 2, 'NAME' => 'Смартфон Alpha',      'ACTIVE' => 'Y', 'PREVIEW_TEXT' => 'Флагманский смартфон'],
    101 => ['ID' => 101, 'IBLOCK_ID' => 2, 'NAME' => 'Чехол Alpha',         'ACTIVE' => 'Y', 'PREVIEW_TEXT' => 'Защитный чехол'],
    102 => ['ID' => 102, 'IBLOCK_ID' => 2, 'NAME' => 'Стекло Alpha',        'ACTIVE' => 'Y', 'PREVIEW_TEXT' => 'Защитное стекло'],
    103 => ['ID' => 103, 'IBLOCK_ID' => 2, 'NAME' => 'Зарядка Alpha 30W',   'ACTIVE' => 'Y', 'PREVIEW_TEXT' => 'Быстрая зарядка'],
    104 => ['ID' => 104, 'IBLOCK_ID' => 2, 'NAME' => 'Наушники Alpha Buds', 'ACTIVE' => 'Y', 'PREVIEW_TEXT' => 'Беспроводные наушники'],
    105 => ['ID' => 105, 'IBLOCK_ID' => 2, 'NAME' => 'Кабель USB-C',        'ACTIVE' => 'N', 'PREVIEW_TEXT' => 'Снят с продажи'],
    106 => ['ID' => 106, 'IBLOCK_ID' => 2, 'NAME' => 'Power Bank Alpha',    'ACTIVE' => 'Y', 'PREVIEW_TEXT' => 'Внешний аккумулятор'],
];

$GLOBALS['__DB']['properties'] = [
    // RELATED_PRODUCTS (множественное) у 100
    '100:RELATED_PRODUCTS' => ['101', '102', '103', '104', '105', '106'],

    // DISCOUNT_PERCENT
    '101:DISCOUNT_PERCENT' => ['10'],
    '102:DISCOUNT_PERCENT' => ['0'],
    '103:DISCOUNT_PERCENT' => ['20'],
    '104:DISCOUNT_PERCENT' => ['15'],
    '105:DISCOUNT_PERCENT' => ['50'],
    '106:DISCOUNT_PERCENT' => ['5'],

    // IN_STOCK
    '101:IN_STOCK' => ['Y'],
    '102:IN_STOCK' => ['Y'],
    '103:IN_STOCK' => ['Y'],
    '104:IN_STOCK' => ['N'],
    '105:IN_STOCK' => ['Y'],
    '106:IN_STOCK' => ['Y'],

    // PRICE
    '101:PRICE' => ['1000'],
    '102:PRICE' => ['500'],
    '103:PRICE' => ['2000'],
    '104:PRICE' => ['5000'],
    '105:PRICE' => ['300'],
    '106:PRICE' => ['1500'],
];
