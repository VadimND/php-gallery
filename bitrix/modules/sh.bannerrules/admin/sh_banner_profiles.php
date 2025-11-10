<?

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require __DIR__ . "/../constants.php";

use Bitrix\Main\Localization\Loc;

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "include.php");

\Bitrix\Main\Loader::IncludeModule(SHDBANNERRULES_MODULE_NAME);



$SHDBANNERRULES_RIGHT = $APPLICATION->GetGroupRight(SHDBANNERRULES_MODULE_NAME);


if ($SHDBANNERRULES_RIGHT == "D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));


$tblObj = new Sh\BannerRules\ProfilesTable();

$oProfilesTable = $tblObj->query();

$map = $tblObj->getMap();

$propParams = array();
$arHeaders = array();

/* @var $field  Bitrix\Main\Entity\Field() */
foreach ($map as $field) {
	$arHeaders[] = array("id" => $field->getName(), "content" => $field->getTitle(), "sort" => $field->getName(), "default" => (count($arHeaders) < 6));
}

$arFilter = array();

if (!empty($_REQUEST["FINDBY"])) {
	$arFilter = array_filter($_REQUEST["FINDBY"], function(&$a) {
		if (is_array($a)) {

			$a = array_filter($a, function($b) {
				$b = (!is_array($b)) ? trim($b) : $b;
				return !empty($b);
			});
		}


		return !empty($a);
	});
}




$sTableID = "sh_banner";
$oSort = new CAdminSorting($sTableID, "ID", "ASC");
$lAdmin = new CAdminList($sTableID, $oSort);
$back_url = '/bitrix/admin/sh_banner_profiles.php';
$url = "/bitrix/admin/sh_banner_profiles_edit.php";

$aContext[] = array(
	"TEXT" => GetMessage("SHDBANNERRULES_ADD"),
	"ICON" => "btn_new",
	"LINK" => $url,
);



$lAdmin->AddAdminContextMenu($aContext, false);


if (($arID = $lAdmin->GroupAction())) {

	if ($_REQUEST['action_target'] == 'selected') {
		$rsData = $oProfilesTable
			->setFilter($arFilter)
			->setOrder(array($by => $order))
			->exec();

		while ($arRes = $rsData->Fetch()) {
			$arID[] = $arRes['ID'];
		}
	}

	foreach ($arID as $propID) {
		if (intval($propID) <= 0) {
			continue;
		}
		switch ($_REQUEST['action']) {
			case "delete":
				$DB->StartTransaction();

				if (!$tblObj->Delete($propID)) {
					$DB->Rollback();
					$error = $APPLICATION->GetException()->GetString();
					$error = (!strlen($error)) ? GetMessage("SHDBANNERRULES_ERR_DEL") . " (&quot;" . htmlspecialchars($propID) . "&quot;)" : $error;
					$lAdmin->AddGroupError($error, $propID);
				}

				$DB->Commit();
				break;
		}
	}
}

$lAdmin->AddHeaders($arHeaders);

$rsData = $oProfilesTable
	->setSelect(array("*"))
	->setFilter($arFilter)
	->setOrder(array($by => $order))
	->exec();

$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();


$lAdmin->NavText($rsData->GetNavPrint(GetMessage("EXCHANGE_MODULE_INTERVALS")));


$events = array();
while ($arRes = $rsData->Fetch()) {

	foreach ($map as $field) {

		if (method_exists($field, "getValues") && count($field->getValues())) {
			$valuesList = $field->getValues();
			$arRes[$field->getName()] = isset($valuesList[$arRes[$field->getName()]]) ? $valuesList[$arRes[$field->getName()]] : $arRes[$field->getName()];
		}
	}

	$contentMenu = array(
		array("ICON" => "edit", "TEXT" => GetMessage("SHDBANNERRULES_EDIT"), "ACTION" => $url . "?PROFILE_ID={$arRes["ID"]}"),
		array("ICON" => "delete", "TEXT" => GetMessage("SHDBANNERRULES_DEL"), "ACTION" => "if(confirm('" . GetMessage("SHDBANNERRULES_ERR_DEL") . "')) " . $lAdmin->ActionDoGroup($arRes["ID"], "delete"))
	);


	$row = & $lAdmin->AddRow($arRes["ID"], $arRes, $url . "?PROFILE_ID={$arRes["ID"]}", GetMessage("MAIN_ADMIN_MENU_EDIT"));

	$row->AddActions($contentMenu);
}

$lAdmin->AddFooter(array(
	array("title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $rsData->SelectedRowsCount()),
	array("counter" => true, "title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"))
);

// Add form with actions
$lAdmin->AddGroupActionTable(Array(
	"delete" => GetMessage("MAIN_ADMIN_LIST_DELETE")
));


$lAdmin->CheckListMode();

$APPLICATION->SetTitle(Loc::GetMessage("SHDBANNERRULES_PROFILES"));

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

?>
<form name="form1" method="GET" action="<?= $APPLICATION->GetCurPage() ?>">
	<?
//тут должно быть в обратном порядке  поля

	$propsArray = array();
	foreach ($map as $field) {
		$propsArray["FINDBY[{$field->getName()}]"] = $field->getTitle();
	}

	$oFilter = new CAdminFilter($sTableID . "_filter", $propsArray);

	$oFilter->Begin();

	?>

	<? foreach ($map as $field) : ?>
		<tr>
			<td><?= $field->getTitle() ?>:</td>
			<td><input type="text" name="<?= "FINDBY[{$field->getName()}]"; ?>" value="<? echo htmlspecialcharsex($FINDBY[$propsCode]) ?>" size="30"></td>
		</tr>
	<? endforeach; ?>

	<?
	$oFilter->Buttons(array("table_id" => $sTableID, "url" => $APPLICATION->GetCurPage()));
	$oFilter->End();

	?>
</form>

<?
$lAdmin->DisplayList();

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
