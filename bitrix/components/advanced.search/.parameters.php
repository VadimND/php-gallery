<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "PAGE_SIZE" => array(
            "NAME" => "Количество элементов на странице",
            "TYPE" => "STRING",
            "DEFAULT" => "20",
            "PARENT" => "BASE",
        ),
        "CACHE_TIME" => array(
            "DEFAULT" => 3600
        ),
    )
);
?>