<?php
/**
 * Условия: Версия 1С-Битрикс - последняя актуальная. Версия PHP - 8.2+
 * 
 * Необходимо вывести все товары из корзины пользователя привязанной к заказу с указанием названия раздела, которому принадлежит товар, названия товара, цены товара со скидкой, базовой цены товара с валюты
 *
 * Необходимо получить результат наиболее оптимальным способом и наименьшими ресурсозатратами сервера.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$orderId = 23369;

$arElements = [];

CModule::IncludeModule('sale');
$res = CSaleOrder::GetList(
    [],
    ["ID" => $orderId],
    false,
    false,
    ['*']
);
while ($arItemOrder = $res->Fetch()) {

    $basket = CSaleBasket::GetList(
        $arOrder = [],
        $arFilter = [
            "ORDER_ID" => $arItemOrder["ID"],
        ],
        $arGroupBy = false,
        $arNavStartParams = false,
        $arSelectFields = ['*']
    );
    while ($arItemBasket = $basket->Fetch()) {

        $Element = \CIblockElement::GetById($arItemBasket["PRODUCT_ID"])->GetNextElement();
        $Element = [
            ...$Element->GetFields(),
            'PROPERTIES' => $Element->GetProperties(),
        ];
        $section = \CIblockSection::getById($Element["IBLOCK_SECTION_ID"])->Fetch();

        $arElements[] = [
            'NAME' => $arItemBasket["PRODUCT_NAME"],
            'SECTION_NAME' => $section['NAME'],
            'PRICE_DISCOUNT' => $arItemBasket["PRICE"],
            'PRICE_BASE' => $arItemBasket["BASE_PRICE"],
            'CURRENCY' => $arItemBasket["CURRENCY"],
        ];
    }
}

echo '<pre>';
print_r($arElements);
echo '</pre>';

/**
 * Для работы использовалась официальная демоверсия интернет-магазина 1С-Битрикс на PHP 8.4
 * 
 * Решение 1 - старый API с максимальной производительностью и минимальной нагрузкой на сервер
 */

// Подключаем необходимые файлы для работы системы
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

// Включаем модули интернет-магазина, каталога и инфоблоков
CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');
CModule::IncludeModule('iblock');

$orderId = 23369;

$arElements = [];

// запрос корзины по номеру заказа
$basket = CSaleBasket::GetList(
    $arOrder = [],
    $arFilter = [
        'ORDER_ID' => $orderId,
    ],
    $arGroupBy = false,
    $arNavStartParams = false,
    $arSelectFields = ['PRODUCT_ID', 'NAME', 'PRICE', 'BASE_PRICE', 'CURRENCY']
);

// Обход каждой позиции в заказе
while ($arItemBasket = $basket->Fetch()) {

    // Учитываем торговые предложения, у них не заданы знаяения для IBLOCK_SECTION_ID
    $parent = CCatalogSku::GetProductInfo($arItemBasket['PRODUCT_ID']);

    $parent ? $productId = $parent['ID'] : $productId = $arItemBasket['PRODUCT_ID'];   

    // Делаем запрос к элементам каталога
    $res = CIBlockElement::GetList(
        [],
        ['ID' => $productId],
        false,
        false,
        ['IBLOCK_SECTION_ID']
    );

    // Запрос идентификатора раздела, если пусто - элемент в корневом разделе    
    if ($el = $res->Fetch()) {
        $sectionId = $el['IBLOCK_SECTION_ID'];
    }    

    // Получаем информацию по разделам
    $section = \CIblockSection::getById($sectionId)->Fetch();

    // Заполняем итоговый массив
    $arElements[] = [
        'NAME' => $arItemBasket['NAME'],
        'SECTION_NAME' => $section ? $section['NAME'] : 'Без раздела',
        'PRICE_DISCOUNT' => $arItemBasket['PRICE'],
        'PRICE_BASE' => $arItemBasket['BASE_PRICE'],
        'CURRENCY' => $arItemBasket['CURRENCY'],
    ];
}

echo '<pre>';
print_r($arElements);
echo '</pre>';

/* 
Решение 2 - с использованием D7 + ORM
*/
use Bitrix\Main\Loader;
use Bitrix\Sale\Order;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

Loader::includeModule('sale');
Loader::includeModule('catalog');
Loader::includeModule('iblock');

$orderId = 23369;

$arElements = [];

// Загружаем заказ и получаем корзину
$order = Order::load($orderId);
$basket = $order->getBasket();

foreach ($basket as $basketItem) {

    $productId = $basketItem->getProductId();

    // Для получения торгового предложения используем старый метод, так как в D7 нет аналога
    $parentInfo = \CCatalogSku::GetProductInfo($productId);

    $parentId = $parentInfo ? $parentInfo['ID'] : $productId;

    // Запрашиваем раздел элемента
    $element = ElementTable::getList([
        'filter' => ['ID' => $parentId],
        'select' => ['ID', 'IBLOCK_SECTION_ID']
    ])->fetch();    

    // Получаем информацию о разделе
    if ($element && $element['IBLOCK_SECTION_ID']) {
        $section = SectionTable::getList([
            'filter' => ['ID' => $element['IBLOCK_SECTION_ID']],
            'select' => ['NAME']
        ])->fetch();        
    }

    $arElements[] = [
        'NAME'           => $basketItem->getField('NAME'),
        'SECTION_NAME'   => $section ? $section['NAME'] : 'Без раздела',
        'PRICE_DISCOUNT' => $basketItem->getPrice(),
        'PRICE_BASE'     => $basketItem->getBasePrice(),
        'CURRENCY'       => $basketItem->getCurrency(),
    ];
}

echo '<pre>';
print_r($arElements);
echo '</pre>';