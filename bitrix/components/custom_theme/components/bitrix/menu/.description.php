<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => "Меню первого раздела", 
    "DESCRIPTION" => "Трехколоночное меню",
    "ICON" => "/images/icon.gif",
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "custom",           // Раздел в списке компонентов
        "NAME" => "Кастомные компоненты", 
    ),
    "COMPLEX" => "N",
);
?>