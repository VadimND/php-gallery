<?
$events = GetModuleEvents(SHDBANNERRULES_MODULE_NAME, "OnModuleUnInstall");
while ($arEvent = $events->Fetch()) {
	if (strlen($arEvent["TO_CLASS"]) <= 0) {
		$arEvent["CALLBACK"] = $arEvent["TO_METHOD"];
	}
	ExecuteModuleEvent($arEvent);
}

?>

<form action="<?= $APPLICATION->GetCurPage() ?>" method="post">
	<?= bitrix_sessid_post() ?>
	<?
	if ($ex = $APPLICATION->GetException()) :
		echo CAdminMessage::ShowMessage(GetMessage("SHDBANNERRULES_UNINSTALL_ERROR") . "<br />" . $ex->GetString());

		?>
		<p>
			<input type="hidden" name="lang" value="<?= LANG ?>">
			<input type="submit" name="" value="<?= GetMessage("MOD_BACK") ?>">	
		</p>
<? else: ?>
		<input type="hidden" name="lang" value="<?= LANG ?>">
		<input type="hidden" name="id" value="<?= SHDBANNERRULES_MODULE_NAME ?>">
		<input type="hidden" name="uninstall" value="Y">
		<input type="hidden" name="step" value="2">
	<?= CAdminMessage::ShowMessage(GetMessage("MOD_UNINST_WARN")) ?>
		<p><?= GetMessage("MOD_UNINST_SAVE") ?></p>
		<p><input type="checkbox" name="savedata" id="savedata" value="Y" checked><label for="savedata"><?= GetMessage("MOD_UNINST_SAVE_TABLES") ?></label></p>
		<input type="submit" name="inst" value="<?= GetMessage("MOD_UNINST_DEL") ?>">
<? endif; ?>
</form>