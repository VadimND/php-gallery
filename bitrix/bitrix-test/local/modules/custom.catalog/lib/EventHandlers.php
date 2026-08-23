<?php

namespace Custom\Catalog;

use Bitrix\Main\Loader;

/**
 * Обработчики событий модуля.
 */
class EventHandlers
{
    /**
     * Статический кеш скидок в рамках хита.
     * @var array
     */
    private static $discountCache = [];

    /**
     * Обработчик OnBeforeIBlockElementUpdate.
     *
     * При изменении элемента каталога нормализует значение скидки:
     * значение должно быть целым числом в диапазоне 0..90.
     */
    public static function onBeforeElementUpdate(&$arFields)
    {
        if ((int)$arFields['IBLOCK_ID'] !== PriceHelper::getCatalogIblockId()) {
            return true;
        }

        if (isset($arFields['PROPERTY_VALUES'])) {
            foreach ($arFields['PROPERTY_VALUES'] as $propId => &$values) {
                // Нормализация значений скидки
                foreach ($values as &$val) {
                    if (is_array($val) && isset($val['VALUE'])) {
                        $val['VALUE'] = self::normalizeDiscount($val['VALUE']);
                    }
                }
                unset($val);
            }
            unset($values);
        }

        return true;
    }

    private static function normalizeDiscount($value)
    {
        $value = (int)$value;
        if ($value < 0) {
            $value = 0;
        }
        if ($value > 90) {
            $value = 90;
        }
        return $value;
    }

    /**
     * Обработчик OnAfterIBlockElementUpdate.
     *
     * Сбрасывает кеш скидок для изменённого элемента.
     */
    public static function onAfterElementUpdate(&$arFields)
    {
        $id = (int)$arFields['ID'];
        // Сброс кеша скидок
        unset(self::$discountCache[$id]);

        return true;
    }

    /**
     * Возвращает скидку с кешированием в рамках хита.
     */
    public static function getCachedDiscount($elementId)
    {
        if (array_key_exists($elementId, self::$discountCache)) {
            return self::$discountCache[$elementId];
        }

        Loader::includeModule('iblock');

        $res = \CIBlockElement::GetProperty(
            PriceHelper::getCatalogIblockId(),
            $elementId,
            [],
            ['CODE' => PriceHelper::DISCOUNT_PROP_CODE]
        );
        $row = $res->Fetch();
        $discount = $row ? (int)$row['VALUE'] : 0;

        self::$discountCache[$elementId] = $discount;

        return $discount;
    }
}
