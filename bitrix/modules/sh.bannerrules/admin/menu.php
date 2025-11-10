<?
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang) - strlen("/admin/menu.php"));
include(GetLangFileName($strPath2Lang . "/lang/", "/install.php"));

if (!defined("SHDBANNERRULES_MODULE_DIR_PATH")) {
	require_once $strPath2Lang . "/include.php";
}

IncludeModuleLangFile(__FILE__);


AddEventHandler("main", "OnBuildGlobalMenu", "addShBannerMenu");

function addShBannerMenu(&$aGlobalMenu, &$aModuleMenu)
{
	global $APPLICATION;


	if ($APPLICATION->GetGroupRight("advertising") != "D") {

		foreach ($aModuleMenu as &$menuParams) {
			if ($menuParams["parent_menu"] == "global_menu_marketing" && $menuParams["section"] == "advertising") {

				$menuParams["items"][] = array(
					"text" => GetMessage("SHDBANNERRULES_PROFILES"),
					"title" => GetMessage("SHDBANNERRULES_PROFILES"),
					"url" => "/bitrix/admin/sh_banner_profiles.php?lang=" . LANGUAGE_ID,
					"more_url" => array(
						"/bitrix/admin/sh_banner_profiles.php",
						"/bitrix/admin/sh_banner_profiles_edit.php"
					),
				);
				break;
			}
		}
	}
}
return false;
