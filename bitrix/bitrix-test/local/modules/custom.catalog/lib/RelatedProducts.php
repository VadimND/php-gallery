<?php

namespace Custom\Catalog;

use Bitrix\Main\Loader;

/**
 * Логика получения связанных товаров.
 *
 * Связанные товары хранятся в множественном свойстве RELATED_PRODUCTS
 * (тип "привязка к элементам") у элемента каталога.
 *
 * Дополнительно связанные товары должны быть:
 *  - активны (ACTIVE = Y);
 *  - в наличии (свойство IN_STOCK = Y);
 *  - отсортированы по итоговой цене (с учётом скидок) по возрастанию.
 */
class RelatedProducts
{
    const RELATED_PROP_CODE = 'RELATED_PRODUCTS';
    const STOCK_PROP_CODE = 'IN_STOCK';
    const DISCOUNT_PROP_CODE = 'DISCOUNT_PERCENT';
    const PRICE_PROP_CODE = 'PRICE';

    /**
     * Возвращает связанные товары для элемента каталога.
     *
     * @param int $elementId ID основного товара
     * @param int $userDiscount Персональная скидка текущего пользователя
     * @return array Список связанных товаров с рассчитанной ценой
     */
    public static function get($elementId, $userDiscount = 0)
    {
        Loader::includeModule('iblock');

        $iblockId = PriceHelper::getCatalogIblockId();

        $relatedIds = self::getRelatedIds($elementId);
        if (empty($relatedIds)) {
            return [];
        }

        // Загружаем все данные одним запросом
        $elements = self::loadRelatedElements($relatedIds, $iblockId);
        if (empty($elements)) {
            return [];
        }

        $result = [];
        foreach ($elements as $element) {
            // Проверка активности
            if ($element['ACTIVE'] !== 'Y') {
                continue;
            }

            // Проверка наличия
            $inStock = $element['PROPERTY_' . self::STOCK_PROP_CODE . '_VALUE'] ?? 'N';
            if ($inStock !== 'Y') {
                continue;
            }

            $basePrice = (float)($element['PROPERTY_' . self::PRICE_PROP_CODE . '_VALUE'] ?? 0);
            if ($basePrice <= 0) {
                continue;
            }

            // Скидка из свойства
            $discount = (int)($element['PROPERTY_' . self::DISCOUNT_PROP_CODE . '_VALUE'] ?? 0);

            // Суммируем с персональной скидкой <= 90%
            $totalDiscount = min($discount + $userDiscount, 90);

            // Расчет итоговой цены
            $finalPrice = $basePrice * (100 - $totalDiscount) / 100;
            $element['FINAL_PRICE'] = round($finalPrice, 2);
            $element['DISCOUNT'] = $totalDiscount;

            $result[] = $element;
        }

        // Теперь пользовательская функция возвращает число
        usort($result, function ($a, $b) {
            return $a['FINAL_PRICE'] <=> $b['FINAL_PRICE'];
        });

        return $result;
    }

    /**
     * Получает ID связанных товаров из множественного свойства.
     */
    private static function getRelatedIds($elementId)
    {
        $ids = [];

        $res = \CIBlockElement::GetProperty(
            PriceHelper::getCatalogIblockId(),
            $elementId,
            ['sort' => 'asc'],
            ['CODE' => self::RELATED_PROP_CODE]
        );

        while ($row = $res->Fetch()) {
            if (!empty($row['VALUE'])) {
                $ids[] = (int)$row['VALUE'];
            }
        }

        return $ids;
    }

     /**
     * Загружает данные элементов одним запросом.
     *
     * @param array $ids Массив ID элементов
     * @param int $iblockId ID инфоблока
     * @return array Массив элементов с заполненными свойствами
     */
    private static function loadRelatedElements(array $ids, $iblockId)
    {
        if (empty($ids)) {
            return [];
        }

        $result = [];

        // Один запрос для всех товаров
        $res = \CIBlockElement::GetList(
            ['ID' => 'ASC'],
            [
                'IBLOCK_ID' => $iblockId,
                'ID' => $ids,
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            [
                'ID',
                'NAME',
                'ACTIVE',
                'IBLOCK_ID',
                'PREVIEW_TEXT',
                'PROPERTY_' . self::STOCK_PROP_CODE,
                'PROPERTY_' . self::PRICE_PROP_CODE,
                'PROPERTY_' . self::DISCOUNT_PROP_CODE
            ]
        );

        while ($row = $res->GetNext()) {
            $result[] = $row;
        }

        return $result;
    }
}