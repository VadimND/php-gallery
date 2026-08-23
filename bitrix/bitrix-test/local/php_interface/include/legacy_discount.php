<?php

/**
 * ВНИМАНИЕ: legacy-код проекта.
 *
 * Исторически на проекте была отдельная "акционная" логика.
 * При сохранении товара к скидке добавляется +5% "сезонной" наценки скидки,
 * если товар помечен как акционный (свойство IS_PROMO = Y).
 *
 * Этот код был написан до появления модуля custom.catalog.
 */

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler(
    'iblock',
    'OnBeforeIBlockElementUpdate',
    'legacyPromoDiscountHandler'
);

function legacyPromoDiscountHandler(&$arFields)
{
    if ((int)$arFields['IBLOCK_ID'] !== 2) {
        return true;
    }

    if (empty($arFields['PROPERTY_VALUES'])) {
        return true;
    }

    // Ищем свойство IS_PROMO и DISCOUNT_PERCENT по их ID
    // ВАЖНО: ID свойств зашиты как константы проекта
    $promoPropId = defined('IS_PROMO_PROP_ID') ? IS_PROMO_PROP_ID : 0;
    $discountPropId = defined('DISCOUNT_PROP_ID') ? DISCOUNT_PROP_ID : 0;

    if (!isset($arFields['PROPERTY_VALUES'][$discountPropId])) {
        return true;
    }

    $isPromo = false;
    if (isset($arFields['PROPERTY_VALUES'][$promoPropId])) {
        foreach ($arFields['PROPERTY_VALUES'][$promoPropId] as $v) {
            $val = is_array($v) ? ($v['VALUE'] ?? '') : $v;
            if ($val === 'Y') {
                $isPromo = true;
            }
        }
    }

    if (!$isPromo) {
        return true;
    }

    // Добавляем сезонную наценку к скидке
    foreach ($arFields['PROPERTY_VALUES'][$discountPropId] as &$val) {
        if (is_array($val) && isset($val['VALUE'])) {
            $val['VALUE'] = (int)$val['VALUE'] + 5;
        } else {
            $val = (int)$val + 5;
        }
    }
    unset($val);

    return true;
}

// Константы ID свойств (значения из конкретной БД проекта)
if (!defined('IS_PROMO_PROP_ID')) {
    define('IS_PROMO_PROP_ID', 5);
}
if (!defined('DISCOUNT_PROP_ID')) {
    define('DISCOUNT_PROP_ID', 4);
}