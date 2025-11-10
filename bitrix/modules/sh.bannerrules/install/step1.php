<?
if (!check_bitrix_sessid())
	return;

global $errors;
$alErrors = "";
if (!empty($errors)) {
	for ($i = 0; $i < count($errors); $i++) {
		$alErrors .= $errors[$i] . "<br>";
	}
}

if (!empty($alErrors)) {
	echo CAdminMessage::ShowMessage(Array("TYPE" => "ERROR", "MESSAGE" => GetMessage("MOD_INST_ERR"), "DETAILS" => $alErrors, "HTML" => true));
} else {
	echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
}

?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="post">
    <p>
        <input type="hidden" name="lang" value="<? echo LANG ?>"/>
        <input type="submit" name="" value="<? echo GetMessage("MOD_BACK") ?>"/>	
    </p>
</form>