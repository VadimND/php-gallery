<?

$tblObj = new \Sh\BannerRules\RulesTable();

$sTableID = $tblObj->getTableName();

if ($_REQUEST['table_id'] == $sTableID) {
	$APPLICATION->RestartBuffer();
}

$oRulesTable = $tblObj->query();

$map = $tblObj->getMap();

$oSort = new CAdminSorting($sTableID, 'ID', 'ASC');
$lAdminList = new CAdminList($sTableID, $oSort);

$back_url = '/bitrix/admin/sh_banner_profiles_edit.php?PROFILE_ID=' . $ID;
$url = '/bitrix/admin/sh_banner_rules_edit.php?lang=' . LANG . "&bxpublic=Y&PROFILE_ID={$ID}";

$checkerMenu = array();
$oChecker = \Sh\BannerRules\Checker::getInstance();
/* @var $checker \Sh\BannerRules\Rules\Intreface */
foreach ($oChecker->getCheckers() as $checker) {
	$checkerMenu[] = array(
		'TEXT' => $checker->getCheckerName(),
		'LINK' => "javascript:ShowEventParams('{$url}&CLASS=" . base64_encode(get_class($checker)) . "')",
		'TITLE' => $checker->getCheckerName(),
	);
}

$aContext[] = array(
	'TEXT' => GetMessage('SHDBANNERRULES_ADD'),
	'ICON' => 'btn_new',
	'MENU' => $checkerMenu
);

$lAdminList->AddAdminContextMenu($aContext, false);

if (($arID = $lAdminList->GroupAction())) {
	if ($_REQUEST['action_target'] == 'selected') {
		$rsData = $oRulesTable
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
			case 'delete':
				$DB->StartTransaction();

				if (!$tblObj->Delete($propID)) {
					$DB->Rollback();
					$error = $APPLICATION->GetException()->GetString();
					$error = (!strlen($error)) ? GetMessage('SHDELIVERY_ERR_DEL') . ' (&quot;' . htmlspecialchars($propID) . '&quot;)' : $error;
					$lAdminList->AddGroupError($error, $propID);
				}

				$DB->Commit();
				break;
		}
	}
}

$propParams = array();
$arHeaders = array();

/* @var $field  Bitrix\Main\Entity\Field() */
foreach ($map as $field) {
	$arHeaders[] = array('id' => $field->getName(), 'content' => $field->getTitle(), 'sort' => $field->getName(), 'default' => (count($arHeaders) < 10));
}

$lAdminList->AddHeaders($arHeaders);
$ids = array($ID);
if (isset($currentValues['PROFILE_ID']) && !empty($currentValues['PROFILE_ID'])) {
	$ids[] = $currentValues['PROFILE_ID'];
}

$arFilter = array('=PROFILE_ID' => $ids);

$oRulesTable = $tblObj->query();
$rsData = $oRulesTable
	->setSelect(array('*'))
	->setFilter($arFilter)
	->setOrder(array($by => $order))
	->exec();

$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();

$lAdminList->NavText($rsData->GetNavPrint(GetMessage('EXCHANGE_MODULE_INTERVALS')));

$events = array();
while ($arRes = $rsData->Fetch()) {
	$class = base64_encode($arRes['CLASS']);
	foreach ($map as $field) {
		if (method_exists($field, 'getValues') && count($field->getValues())) {
			$valuesList = $field->getValues();
			$arRes[$field->getName()] = isset($valuesList[$arRes[$field->getName()]]) ? $valuesList[$arRes[$field->getName()]] : $arRes[$field->getName()];
		}
	}

	$contentMenu = array(
		array('ICON' => 'edit', 'TEXT' => GetMessage('SHDELIVERY_EDIT'), 'ACTION' => "ShowEventParams('" . $url . "&ID={$arRes['ID']}&CLASS={$class}')"),
		array('ICON' => 'delete', 'TEXT' => GetMessage('SHDELIVERY_DEL'), 'ACTION' => "if(confirm('" . GetMessage('SHDELIVERY_ERR_DEL') . "')) " . $lAdminList->ActionDoGroup($arRes['ID'], 'delete', "PROFILE_ID={$ID}"))
	);

	$row = &$lAdminList->AddRow($arRes['ID'], $arRes, "javascript:ShowEventParams('{$url}&ID={$arRes['ID']}&CLASS={$class}')", GetMessage('MAIN_ADMIN_MENU_EDIT'));

	$row->AddActions($contentMenu);
}

$lAdminList->AddFooter(array(
	array('title' => GetMessage('MAIN_ADMIN_LIST_SELECTED'), 'value' => $rsData->SelectedRowsCount()),
	array('counter' => true, 'title' => GetMessage('MAIN_ADMIN_LIST_CHECKED'), 'value' => '0')
));

// Add form with actions
$lAdminList->AddGroupActionTable(Array(
	'delete' => GetMessage('MAIN_ADMIN_LIST_DELETE')
));

$lAdminList->AddFooter(array(
	array('title' => GetMessage('MAIN_ADMIN_LIST_SELECTED'), 'value' => $rsData->SelectedRowsCount()),
	array('counter' => true, 'title' => GetMessage('MAIN_ADMIN_LIST_CHECKED'), 'value' => '0')
));

$lAdminList->CheckListMode();
$lAdminList->DisplayList();

if ($_REQUEST['table_id'] == $sTableID) {
	die();
}

?>
<script type="text/javascript">
	function ShowEventParams(url)
	{
		(new BX.CAdminDialog({
//            'content_post': '&backurl=/bitrix/admin/',
			'content_url': url + '&backurl=/bitrix/admin/',
			'draggable': true,
			'resizable': true
		})).Show();
	}
</script>