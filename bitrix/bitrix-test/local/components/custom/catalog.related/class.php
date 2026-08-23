<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Custom\Catalog\RelatedProducts;

/**
 * Компонент вывода связанных товаров.
 *
 * Параметры:
 *  ELEMENT_ID   — ID основного товара
 *  CACHE_TIME   — время кеширования
 */
class CatalogRelatedComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['ELEMENT_ID'] = (int)$arParams['ELEMENT_ID'];
        $arParams['CACHE_TIME'] = isset($arParams['CACHE_TIME']) ? (int)$arParams['CACHE_TIME'] : 3600;
        return $arParams;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('custom.catalog')) {
            ShowError('Модуль custom.catalog не установлен');
            return;
        }

        $elementId = $this->arParams['ELEMENT_ID'];
        if ($elementId <= 0) {
            return;
        }

        // Персональная скидка пользователя из GET (для промо-ссылок)
        $request = Application::getInstance()->getContext()->getRequest();
        $userDiscount = (int)$request->get('promo_discount');

        if ($this->startResultCache($this->arParams['CACHE_TIME'], $elementId)) {
            $related = RelatedProducts::get($elementId, $userDiscount);

            $this->arResult['ITEMS'] = $related;
            $this->arResult['COUNT'] = count($related);

            $this->includeComponentTemplate();
        }
    }
}
