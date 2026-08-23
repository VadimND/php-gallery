<?php

namespace Custom\Catalog;

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\ElementPropertyTable;

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

        $result = [];
        foreach ($relatedIds as $relId) {
            $element = self::loadElement($relId, $iblockId);
            if (!$element) {
                continue;
            }

            if ($element['ACTIVE'] !== 'Y') {
                continue;
            }

            $inStock = self::getStockValue($relId);
            if ($inStock !== 'Y') {
                continue;
            }

            $basePrice = self::getBasePrice($relId);

            $element['FINAL_PRICE'] = PriceHelper::calculateFinalPrice(
                $element,
                $basePrice,
                $userDiscount
            );

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

    private static function loadElement($id, $iblockId)
    {
        // Используем старый API для получения значения скидки
       $res = \CIBlockElement::GetList(
        [],
        ['ID' => $id, 'IBLOCK_ID' => $iblockId],
        false,
        false,
        [
            'ID',
            'NAME',
            'ACTIVE',
            'IBLOCK_ID',
            'PREVIEW_TEXT',
            'PROPERTY_DISCOUNT_PERCENT'
        ]);

        return $res->fetch();
    }

    private static function getStockValue($id)
    {
        $res = \CIBlockElement::GetProperty(
            PriceHelper::getCatalogIblockId(),
            $id,
            [],
            ['CODE' => self::STOCK_PROP_CODE]
        );
        $row = $res->Fetch();
        return $row ? $row['VALUE'] : 'N';
    }

    private static function getBasePrice($id)
    {
        // Цена хранится в свойстве PRICE (для простоты, без модуля catalog)
        $res = \CIBlockElement::GetProperty(
            PriceHelper::getCatalogIblockId(),
            $id,
            [],
            ['CODE' => 'PRICE']
        );
        $row = $res->Fetch();
        return $row ? (float)$row['VALUE'] : 0.0;
    }
}