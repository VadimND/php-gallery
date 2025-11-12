<?
namespace R52\AcceptCookies;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;

class Main {

    static function appendScriptsToPage() {
        if (!defined("ADMIN_SECTION") && $ADMIN_SECTION !== true) {

            $module_id = pathinfo(dirname(__DIR__))["basename"];
            $siteId = SITE_ID;

            Asset::getInstance()->addString(
                "<script id=\"".str_replace(".", "_", $module_id)."-params\" data-params='".json_encode(
                array(
                    "settings" => array(
                        "switch_on" => Option::get($module_id, "switch_on_" . $siteId, "Y"),
                        "linkShow" => Option::get($module_id, "linkShow_" . $siteId, "N"),
                    ),
                    "design" => array(
                        "block-align" => Option::get($module_id, "block-align_" . $siteId, "left"),
                        "text-align" => Option::get($module_id, "text-align_" . $siteId, "left"),
                        "indent" => Option::get($module_id, "indent_" . $siteId),
                        "padding" => Option::get($module_id, "padding_" . $siteId),
                        "width" => Option::get($module_id, "width_" . $siteId),
                        "radius" => Option::get($module_id, "radius_" . $siteId),
                        "color-1" => Option::get($module_id, "color-1_" . $siteId, "#0150a5"),
                        "color-2" => Option::get($module_id, "color-2_" . $siteId, "#23923d"),
                        "text-color" => Option::get($module_id, "text-color_" . $siteId, "#ffffff"),
                    ),
                    "text" => array(
                        "mainText" => Option::get($module_id, "mainText_" . $siteId, "Этот веб-сайт использует файлы cookie, чтобы вы могли максимально эффективно использовать наш веб-сайт."),
                        "linkText" => Option::get($module_id, "linkText_" . $siteId, "Узнать больше"),
                        "linkPath" => Option::get($module_id, "linkPath_" . $siteId, "#"),
                        "settingsTitle" => Option::get($module_id, "settingsTitle_" . $siteId, "Выберите настройки cookie"),
                        "settingsCheckbox1Text" => Option::get($module_id, "settingsCheckbox1Text_" . $siteId, "Минимальные"),
                        "settingsCheckbox2Text" => Option::get($module_id, "settingsCheckbox2Text_" . $siteId, "Аналитические/Функциональные"),
                        "btn1Text" => Option::get($module_id, "btn1Text_" . $siteId, "Принять"),
                        "btn2Text" => Option::get($module_id, "btn2Text_" . $siteId, "Настроить"),
                    ),
                )
                )."'></script>",
                true
            );

            Asset::getInstance()->addCss("/bitrix/css/".$module_id."/style.css");
            Asset::getInstance()->addJs("/bitrix/js/".$module_id."/script.js");
        }
        
        return false;
    }
}
?>