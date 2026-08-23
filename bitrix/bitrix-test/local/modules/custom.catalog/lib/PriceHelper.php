<?php

namespace Custom\Catalog;

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;

/**
 * Хелпер для расчёта цен и скидок каталога.
 *
 * Используется в компоненте catalog.related и в обработчиках событий.
 */
class PriceHelper
{
    const DISCOUNT_PROP_CODE = 'DISCOUNT_PERCENT';

    /**
     * Возвращает итоговую цену элемента с учётом скидки бренда и персональной скидки.
     *
     * @param array $element  Массив элемента инфоблока (должен содержать ID, свойства)
     * @param float $basePrice Базовая цена
     * @param int   $userDiscount Персональная скидка пользователя в процентах
     * @return float
     */
    public static function calculateFinalPrice(array $element, $basePrice, $userDiscount = 0)
    {
        $discount = 0;

        // Скидка из свойства элемента
        if (isset($element['PROPERTY_' . self::DISCOUNT_PROP_CODE . '_VALUE'])) {
            $discount = (int)$element['PROPERTY_' . self::DISCOUNT_PROP_CODE . '_VALUE'];
        }

        // Суммируем персональную скидку пользователя
        $totalDiscount = $discount + $userDiscount;

        // Ограничение: максимальная скидка 90%
        if ($totalDiscount > 90) {
            $totalDiscount = 90;
        }

        $final = $basePrice - ($basePrice * $totalDiscount / 100);

        return round($final, 2);
    }

    /**
     * Возвращает бренд-скидку для набора элементов.
     * Возвращает массив [ELEMENT_ID => DISCOUNT_PERCENT].
     */
    public static function getBrandDiscounts(array $elementIds)
    {
        Loader::includeModule('iblock');

        $result = [];

        foreach ($elementIds as $id) {
            $res = ElementTable::getList([
                'select' => ['ID', 'NAME', 'IBLOCK_ID'],
                'filter' => ['=ID' => $id],
            ]);
            $row = $res->fetch();
            if ($row) {
                // Скидка бренда рассчитывается отдельным запросом
                $result[$id] = self::getDiscountForElement($id);
            }
        }

        return $result;
    }

    private static function getDiscountForElement($id)
    {
        $res = \CIBlockElement::GetProperty(
            self::getCatalogIblockId(),
            $id,
            [],
            ['CODE' => self::DISCOUNT_PROP_CODE]
        );
        $prop = $res->Fetch();
        return $prop ? (int)$prop['VALUE'] : 0;
    }

    public static function getCatalogIblockId()
    {
        // ID инфоблока каталога
        return 2;
    }
}
