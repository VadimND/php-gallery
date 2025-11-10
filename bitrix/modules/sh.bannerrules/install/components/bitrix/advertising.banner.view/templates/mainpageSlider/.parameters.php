<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (!CModule::IncludeModule("advertising"))
    return;

Bitrix\Main\Config\Option::set("advertising", "SHOW_COMPONENT_PREVIEW", "N");


$arTemplateParameters = array(
 "PARAMETERS" => array(
  "BACKGROUND" => Array(
   "NAME"    => "Тип банера",
   "TYPE"    => "LIST",
   "VALUES"  => array(
    'image'      => "Картинка",
    'bannertext' => "Банер с текстом"
   ),
   "REFRESH" => 'Y',
   "SORT"    => 10
  )
 )
);

$arTemplateParameters["PARAMETERS"]["LINK_URL"] = Array(
 "NAME"    => "Ссылка",
 "TYPE"    => "STRING",
 "DEFAULT" => "",
 "SORT"    => 50
);


$arTemplateParameters["PARAMETERS"]["IMG"] = Array(
 "NAME" => "Подложка банера",
 "TYPE" => "IMAGE",
 "SORT" => 20
);

$arTemplateParameters["PARAMETERS"]["TITLE"] = Array(
 "NAME"    => "Заголовок (alt картинки)",
 "TYPE"    => "STRING",
 "DEFAULT" => "",
 "SORT"    => 20
);


if ($arCurrentValues['BACKGROUND'] == 'bannertext') {

    $arTemplateParameters["PARAMETERS"]["TEXT"] = Array(
     "NAME"    => "Текст банера",
     "TYPE"    => "HTML",
     "DEFAULT" => "",
     "SORT"    => 40
    );

    $arTemplateParameters["PARAMETERS"]["BUTTON_TEXT"] = Array(
     "NAME"    => "Текст на кнопке",
     "TYPE"    => "STRING",
     "DEFAULT" => "",
     "SORT"    => 40
    );
}

$arTemplateParameters["SETTINGS"]["MULTIPLE"] = 'N';

$arTemplateParameters["SETTINGS"]["PREVIEW"] = 'N';
