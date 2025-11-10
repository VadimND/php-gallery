<?

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require __DIR__ . "/../constants.php";

Bitrix\Main\Localization\Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . SHDBANNERRULES_MODULE_DIR_PATH . "include.php");


\Bitrix\Main\Loader::IncludeModule(SHDBANNERRULES_MODULE_NAME);

$shBannersRIGHT = $APPLICATION->GetGroupRight(SHDBANNERRULES_MODULE_NAME);


if ($shBannersRIGHT == "D") {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}


$back_url = '/bitrix/admin/sh_banner_profiles_edit.php?PROFILE_ID=' . $_REQUEST["PROFILE_ID"];




$className = $_REQUEST["CLASS"] && $_SERVER["REQUEST_METHOD"] != "POST" ? base64_decode($_REQUEST["CLASS"]) : $_REQUEST["CLASS"];

$oChecker = \Sh\BannerRules\Checker::getInstance();

if (empty($className) || !isset($oChecker->getCheckers()[$className])) {
	return false;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/admin_tools.php");

$checker = $oChecker->getCheckers()[$className];

$map = $checker->getMap();


$oRulesParams = $checker->query();

ClearVars("str_");

if (!empty($ID)) {

	$currentValues = $oRulesParams
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
	$shBannersRIGHT == "W" // проверка наличия прав на запись для модуля
	&&
	check_bitrix_sessid()  // проверка идентификатора сессии
) {


	$arFields = array();

	/* @var $field  Bitrix\Main\Entity\Field() */
	foreach ($map as $field) {
		if ($field->getName() == "ID") {
			continue;
		}
		if (isset($_POST[$field->getName()])) {
			$arFields[$field->getName()] = $field->getName() == "CLASS" ? $className : $_POST[$field->getName()];
		}
	}


	$res = false;

	// сохранение данных 
	if ($ID > 0) {
		$resultSave = $checker->Update($ID, $arFields);
		$res = $ID = $resultSave->isSuccess() ? $resultSave->getId() : 0;
	} else {
		$resultSave = $checker->Add($arFields);

		$res = $ID = $resultSave->isSuccess() ? $resultSave->getId() : 0;
	}


	if (!$res) {

		// если в процессе сохранения возникли ошибки - полу
		$errors .= "Не получилось сохранить";
		$errors .= implode(",\n ", $resultSave->getErrors());
		$bVarsFromForm = true;
	} else {
		LocalRedirect($back_url);
	}
}


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

if ($bVarsFromForm) {
	$DB->InitTableVarsForEdit("b_sh_delivery_times", "", "str_");
} elseif (!$ID) {
	foreach ($map as $field) {
		if ($field->getName() == "ID") {
			continue;
		}

		$currentValues[$field->getName()] = $field->getDefaultValue();
	}
}

$currentValues["CLASS"] = $className;
$currentValues["PROFILE_ID"] = $_REQUEST["PROFILE_ID"];


$disabledField = array("PROFILE_ID", "CLASS");


$context = new CAdminContextMenu(array());
$context->Show();

if (strlen($errors))
	CAdminMessage::ShowMessage(array(
		"MESSAGE" => GetMessage("SELLERS_ERROR_DETECTED"),
		"DETAILS" => $errors,
		"HTML" => true,
		"TYPE" => $type,
	));

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
			<? if (get_class($field) != "Sh\Delivery\PointsField"): ?>
				<td width="40%" class="adm-detail-content-cell-l"><?= $field->getTitle() ?></td>
				<td width="60%" class="adm-detail-content-cell-r">
				<? else: ?>
				<td colspan="2"><?= $field->getTitle() ?></td></tr>
			<tr>
				<td colspan="2">
				<? endif ?>
				<?
				switch (get_class($field)):


					case "Bitrix\Main\ORM\Fields\DateField":
					case "Bitrix\Main\Entity\DateField":

						?>
						<input type="text" class="typeinput" name="<?= $field->getName() ?>" size="12">
						<?= Calendar($field->getName(), "curform") ?>
						<?
						break;

					case "Bitrix\Main\ORM\Fields\EnumField":
					case "Bitrix\Main\Entity\EnumField":
						$values = $field->getValues();

						?>
						<select name="<?= $field->getName() ?>" <?= in_array($field->getName(), $disabledField) ? "disabled" : ""; ?>>
							<? foreach ($values as $valueID => $valueName): ?>
								<option value="<?= $valueID ?>" <?= $currentValues[$field->getName()] == $valueID ? "selected" : "" ?> ><?= $valueName ?></option>
						<? endforeach; ?>
						</select>
						<? if (in_array($field->getName(), $disabledField)): ?>
							<input name="<?= $field->getName() ?>" type="hidden" value="<?= $currentValues[$field->getName()] ?>" />
						<? endif; ?>
						<?
						break;

					case "Bitrix\Main\ORM\Fields\BooleanField":
					case "Bitrix\Main\Entity\BooleanField":

						?>
						<input  type="hidden" name="<?= $field->getName() ?>" value="0"/>
						<input  type="checkbox" name="<?= $field->getName() ?>" value="1" <?= ($currentValues[$field->getName()] ? "checked" : ""); ?>/>
						<?
						break;
					case "Sh\Delivery\PointsField":

						?> 

						<div class="poligonYandexMap"></div>

						<?
						break;

					case "Bitrix\Main\ORM\Fields\IntegerField":
					case "Bitrix\Main\Entity\IntegerField":

						?>
						<input  type="number" name="<?= $field->getName() ?>" value="<?= strlen($currentValues[$field->getName()]) ? $currentValues[$field->getName()] : ""; ?>"/>
						<?
						break;


					case "Bitrix\Main\ORM\Fields\FloatField":
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
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");

