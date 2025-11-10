<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang) - strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang . "/lang/", "/install.php"));

if (!defined("SHDBANNERRULES_MODULE_DIR_PATH")) {
	require_once $strPath2Lang . "/include.php";
}
if (class_exists(str_replace(".", "_", SHDBANNERRULES_MODULE_NAME))) {
	return;
}

Class sh_bannerrules extends CModule
{

	var $MODULE_ID = SHDBANNERRULES_MODULE_NAME;
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_GROUP_RIGHTS = "Y";

	function sh_bannerrules()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = GetMessage("SHDBANNERRULES_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("SHDBANNERRULES_INSTALL_DESCRIPTION");
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		$GLOBALS["errors"] = $this->errors;


		$APPLICATION->IncludeAdminFile(GetMessage("SHDBANNERRULES_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "install/step1.php");
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;
		$step = IntVal($step);
		if ($step < 2) {
			$APPLICATION->IncludeAdminFile(GetMessage("SHDBANNERRULES_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "install/unstep1.php");
		} elseif ($step == 2) {

			$this->UnInstallDB(array(
				"savedata" => $_REQUEST["savedata"],
			));
			$this->UnInstallFiles();

			$GLOBALS["errors"] = $this->errors;
			$APPLICATION->IncludeAdminFile(GetMessage("SHDBANNERRULES_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "install/unstep2.php");
		}
	}

	function InstallDB()
	{
		global $DB, $APPLICATION;

		$this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "install/db/mysql/install.sql");

		if ($this->errors !== false) {
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}

		RegisterModule(SHDBANNERRULES_MODULE_NAME);

		RegisterModuleDependences("advertising", "OnBeforeBannerUpdate", SHDBANNERRULES_MODULE_NAME, "\\Sh\\BannerRules\\Events", "checkBannerParams");



		return true;
	}

	function UnInstallDB($arParams = array())
	{
		CModule::IncludeModule($this->MODULE_ID);

		global $DB, $APPLICATION;

		$this->errors = false;
		if (array_key_exists("savedata", $arParams) && $arParams["savedata"] != "Y") {
			$this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "install/db/mysql/uninstall.sql");
			if ($this->errors !== false) {
				$APPLICATION->ThrowException(implode("", $this->errors));
				return false;
			}
		}

		COption::RemoveOption(SHDBANNERRULES_MODULE_NAME);
		UnRegisterModule(SHDBANNERRULES_MODULE_NAME);
		UnRegisterModuleDependences("advertising", "OnBeforeBannerUpdate", SHDBANNERRULES_MODULE_NAME, "\\Sh\\BannerRules\\Events", "checkBannerParams");
		return true;
	}

	function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
		return true;
	}
}
