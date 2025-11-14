# Компонент вывода карточки пользователя

## Описание
Компонент выводит на страницу обширное меню.

## Размещение
Файл с меню .belarus_section.menu.php размещается в корне сайта. Здесь же можно задавать порядок пунктов, редактировать и создавать новые пункты. В настройках управдения структурой создается новый тип меню - 

## Использование на странице
```<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "three_columns",
    array(
        "ROOT_MENU_TYPE" => "belarus_section",
        "MAX_LEVEL" => 1,
        "USE_EXT" => "Y",
        "MENU_CACHE_TYPE" => "N",  
        "CACHE_SELECTED_ITEMS" => "N"
    ),
    false,
    array("HIDE_ICONS" => "Y")
);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
```



