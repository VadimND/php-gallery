<?

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require __DIR__ . "/../constants.php";

use Bitrix\Main\Localization\Loc;

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "include.php");


\Bitrix\Main\Loader::IncludeModule(SHDBANNERRULES_MODULE_NAME);

$pfilter_RIGHT = $APPLICATION->GetGroupRight(SHDBANNERRULES_MODULE_NAME);


if ($pfilter_RIGHT == "D") {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}


$back_url = '/bitrix/admin/PROFILE_ID.php?ID=' . $_REQUEST["PROFILE_ID"] . "&tabControl_active_tab=edit40";



require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/admin_tools.php");

$tblObj = new Sh\BannerRules\ProfilesTable();
$map = $tblObj->getMap();
$oProfilesTable = new \Bitrix\Main\Entity\Query(Sh\BannerRules\ProfilesTable::getEntity());

ClearVars("str_");

$ID = $_REQUEST["PROFILE_ID"] ?: 0;

if (!empty($ID)) {

	$currentValues = $oProfilesTable
		->setSelect(array("*"))
		->setFilter(array("=ID" => $ID))
		->exec()
		->fetch();

	$ID = 0;

	if ($currentValues) {
		$ID = $currentValues["ID"];
	}
}


$aTabs = array();
$aTabs[] = array(
	"DIV" => "edit10",
	"TAB" => GetMessage("SHDBANNERRULES_MAIN_OPTION"),
	"ICON" => "",
	"TITLE" => GetMessage("SHDBANNERRULES_MAIN_OPTION"),
);

$tabControl = new CAdminTabControl(("tabControl" . time()), $aTabs);


if (
	$_SERVER["REQUEST_METHOD"] == "POST" // проверка метода вызова страницы
	&&
	($save != "" || $apply != "") // проверка нажатия кнопок "Сохранить" и "Применить"
	&&
	$pfilter_RIGHT == "W"		  // проверка наличия прав на запись для модуля
	&&
	check_bitrix_sessid()	 // проверка идентификатора сессии
) {


	$arFields = array();

	/* @var $field  Bitrix\Main\Entity\Field() */
	foreach ($map as $field) {
		if ($field->getName() == "ID") {
			continue;
		}
		if (isset($_POST[$field->getName()])) {
			$arFields[$field->getName()] = $_POST[$field->getName()];
		}
	}

	$res = false;

	// сохранение данных 
	if ($ID > 0) {
		$resultSave = $tblObj->Update($ID, $arFields);
		$res = $ID = $resultSave->isSuccess() ? $resultSave->getId() : 0;
	} else {
		$resultSave = $tblObj->Add($arFields);
		$res = $ID = $resultSave->isSuccess() ? $resultSave->getId() : 0;
	}

	if (!$res) {

		// если в процессе сохранения возникли ошибки - полу
		$errors .= "Не получилось сохранить";
		$errors .= implode(",\n ", $resultSave->getErrors());
		$bVarsFromForm = true;
	} else {
		if ($apply != "") { // если была нажата кнопка "Применить" - отправляем обратно на форму.
			LocalRedirect("/bitrix/admin/sh_banner_profiles_edit.php?PROFILE_ID=" . $ID . "&lang=" . LANG);
		} else { // если была нажата кнопка "Сохранить" - отправляем к списку элементов.
			LocalRedirect("/bitrix/admin/sh_banner_profiles.php?lang=" . LANG);
		}
	}
}


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

if ($bVarsFromForm) {
	$DB->InitTableVarsForEdit("b_sh_banner", "", "str_");
} elseif (!$ID) {
	foreach ($map as $field) {
		if ($field->getName() == "ID") {
			continue;
		}
		$currentValues[$field->getName()] = $field->getDefaultValue();
	}
}



$aMenu = array(
	array(
		"TEXT" => GetMessage("SHDBANNERRULES_PROFILES"),
		"TITLE" => GetMessage("SHDBANNERRULES_PROFILES"),
		"LINK" => "/bitrix/admin/sh_banner_profiles.php?lang=" . LANG,
		"ICON" => "btn_list"
	)
);

if (intval($ID) > 0) {

	$aMenu[] = array(
		"TEXT" => GetMessage("SHDBANNERRULES_ADD"),
		"TITLE" => GetMessage("SHDBANNERRULES_ADD"),
		"LINK" => "sh_banner_profiles_edit.php?lang=" . LANG,
		"ICON" => "btn_new"
	);

	$aMenu[] = array("SEPARATOR" => "Y");

	$aMenu[] = array(
		"TEXT" => GetMessage("SHDBANNERRULES_DELETE"),
		"TITLE" => GetMessage("SHDBANNERRULES_DELETE"),
		"LINK" => "javascript:if(confirm('" . GetMessage("SHDBANNERRULES_ERR_DEL") . "')) window.location='/bitrix/admin/sh_banner_profiles.php?ID=" . $ID . "&action=delete&lang=" . LANG . "&" . bitrix_sessid_get() . "';",
		"ICON" => "btn_delete"
	);
}


$context = new CAdminContextMenu($aMenu);
$context->Show();

if (strlen($errors))
	CAdminMessage::ShowMessage(array(
		"MESSAGE" => GetMessage("SELLERS_ERROR_DETECTED"),
		"DETAILS" => $errors,
		"HTML" => true,
		"TYPE" => $type,
	));



/* @var $APPLICATION CMain\ */
$APPLICATION->SetTitle(Loc::getMessage("SHDBANNERRULES_PROFILES") . " - " . Loc::getMessage("SHDBANNERRULES_EDIT"));

?>




<form method="POST" id="form" name="form" enctype="multipart/form-data"   action="<?= $APPLICATION->GetCurPageParam() ?>" >
<?= bitrix_sessid_post(); ?>


    <input type="hidden" name="ID" value="<?= intval($ID) ?>">

<?
$tabControl->Begin();
$tabControl->BeginNextTab();

?>
	<? if (intval($ID) > 0): ?>
		<tr>
			<td class="adm-detail-content-cell-l">ID</td>
			<td class="adm-detail-content-cell-r"><?= $ID ?></td>
		</tr>
<? endif; ?>

	<? foreach ($map as $field) : ?>    
		<? if ($field->getName() == "ID") continue; ?>
		<tr>
			<td width="40%" class="adm-detail-content-cell-l"><?= $field->getTitle() ?></td>
			<td width="60%" class="adm-detail-content-cell-r">

	<?
	switch (get_class($field)):
		case "Bitrix\Main\Entity\DateField":

			?>
						<input type="text" class="typeinput" name="<?= $field->getName() ?>" size="12">
						<?= Calendar($field->getName(), "curform") ?>
						<?
						break;
					case "Bitrix\Main\Entity\EnumField":
						$values = $field->getValues();

						?>

						<select name="<?= $field->getName() ?>">
							<? foreach ($values as $valueID => $valueName): ?>
								<option value="<?= $valueID ?>" <?= $currentValues[$field->getName()] == $valueID ? "selected" : "" ?> ><?= $valueName ?></option>
							<? endforeach; ?>
						</select>
						<?
						break;
					case "Bitrix\Main\Entity\BooleanField":

						?>
						<input  type="hidden" name="<?= $field->getName() ?>" value="0"/>
						<input  type="checkbox" name="<?= $field->getName() ?>" value="1" <?= ($currentValues[$field->getName()] ? "checked" : ""); ?>/>
						<?
						break;
					case "Bitrix\Main\Entity\IntegerField":

						?>
						<input  type="number" name="<?= $field->getName() ?>" value="<?= strlen($currentValues[$field->getName()]) ? $currentValues[$field->getName()] : ""; ?>"/>
						<?
						break;
					case "Bitrix\Main\Entity\FloatField":
					default :

						?>
						<input  type="text" name="<?= $field->getName() ?>" value="<?= htmlspecialchars(strlen($currentValues[$field->getName()]) ? $currentValues[$field->getName()] : ""); ?>"/>
		<? endswitch; ?>
			</td>
		</tr>
	<? endforeach; ?>



	<?
	$tabControl->EndTab();
	$tabControl->Buttons(array("disabled" => false, "back_url" => $back_url));
	$tabControl->End();

	?>
</form>


<? if (intval($ID) > 0): ?>
	<h2><?= Loc::getMessage("SHDBANNERRULES_DAY_CONFIG"); ?></h2>
	<? require_once 'sh_banner_rules.php'; ?>

<? endif; ?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");

