<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

CJSCore::init('color_picker');

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

Loader::includeModule($module_id);

$arSites = array();
$rsSites = CSite::GetList($by="sort", $order="desc", array());
while ($arSite = $rsSites->Fetch())
{
  array_push($arSites, $arSite);
}

$aTabs = array();

foreach ($arSites as $site) {
    $moduleParams = array(
        "DIV" => "edit_" . $site["LID"],
        "TAB" => Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_TAB_NAME") . " " . $site["LID"],
        "TITLE" => Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_TAB_NAME") . " " . $site["LID"],
        "OPTIONS" => array(
    
            Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_TAB_COMMON"),
    
            array(
                "switch_on_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_TAB_SWITCH_ON"),
                "N",
                array("checkbox")
            ),
    
            Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_TEXT_SETTINGS"),
    
            array(
                "mainText_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_MAIN_TEXT"),
                "Этот веб-сайт использует файлы cookie, чтобы вы могли максимально эффективно использовать наш веб-сайт.",
                array("textarea", "5", "50")
            ),
            array(
                "linkText_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_LINK_TEXT"),
                "Узнать больше",
                array("text", "50")
            ),
            array(
                "linkPath_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_LINK_PATH"),
                "#",
                array("text", "50")
            ),
            array(
                "linkShow_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_LINK_STATUS"),
                "N",
                array("checkbox")
            ),
            array(
                "settingsTitle_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_SETTINGS_TITLE_TEXT"),
                "Выберите настройки cookie",
                array("text", "50")
            ),
            array(
                "settingsCheckbox1Text_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_SETTINGS_CHECKBOX1_TEXT"),
                "Минимальные",
                array("text", "50")
            ),
            array(
                "settingsCheckbox2Text_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_SETTINGS_CHECKBOX2_TEXT"),
                "Аналитические/Функциональные",
                array("text", "50")
            ),
            array(
                "btn1Text_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_BTN1_TEXT"),
                "Принять",
                array("text", "50")
            ),
            array(
                "btn2Text_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_BTN2_TEXT"),
                "Настроить",
                array("text", "50")
            ),
    
            Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_STYLES_SETTINGS"),
            
            array(
                "block-align_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_BLOCK_ALIGN"),
                "left",
                array("selectbox",
                    array(
                        "left" => Loc::getMessage("R52_ACCEPTCOOKIES_TEXT_ALIGN_LEFT"),
                        "center" => Loc::getMessage("R52_ACCEPTCOOKIES_TEXT_ALIGN_CENTER"),
                        "right" => Loc::getMessage("R52_ACCEPTCOOKIES_TEXT_ALIGN_RIGHT"),
                    )
                )
            ),
            array(
                "text-align_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_TEXT_ALIGN"),
                "left",
                array("selectbox",
                    array(
                        "left" => Loc::getMessage("R52_ACCEPTCOOKIES_TEXT_ALIGN_LEFT"),
                        "center" => Loc::getMessage("R52_ACCEPTCOOKIES_TEXT_ALIGN_CENTER"),
                        "right" => Loc::getMessage("R52_ACCEPTCOOKIES_TEXT_ALIGN_RIGHT"),
                    )
                )
            ),
            array(
                "indent_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_INDENT"),
                "",
                array("text", "4")
            ),
            array(
                "padding_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_PADDING"),
                "",
                array("text", "4")
            ),
            array(
                "width_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_WIDTH"),
                "",
                array("text", "4")
            ),
            array(
                "radius_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_RADIUS"),
                "",
                array("text", "4")
            ),
            array(
                "color-1_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_COLOR1"),
                "#0150a5",
                array("text", "4")
            ),
            array(
                "color-2_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_COLOR2"),
                "#23923d",
                array("text", "4")
            ),
            array(
                "text-color_" . $site["LID"],
                Loc::getMessage("R52_ACCEPTCOOKIES_OPTIONS_TEXT_COLOR"),
                "#ffffff",
                array("text", "4")
            ),
       )
    );

    array_push($aTabs, $moduleParams);
}

if ($request->isPost() && check_bitrix_sessid()) {
    if ($request["apply"]) {
        foreach ($aTabs as $aTab){
            __AdmSettingsSaveOptions($module_id, $aTab["OPTIONS"]);
        }
    } elseif ($request["default"]) {        
        foreach ($aTabs as $aTab){
            foreach ($aTab["OPTIONS"] as $arOption) {
                if (is_array($arOption)) {
                    if (substr($arOption[0], 0, 6) === "switch") {
                        Option::set($module_id, $arOption[0], 'Y');
                    } else {
                        Option::set($module_id, $arOption[0], $arOption[2]);
                    }
                };
            }
        }
    }
    
   LocalRedirect( $APPLICATION->GetCurPage()."?mid=".$module_id . "&lang=".LANG . "&tabControl_active_tab=".$_REQUEST["tabControl_active_tab"] );
}

$tabControl = new CAdminTabControl("tabControl", $aTabs);
  
$tabControl->Begin();
?>

<form id="acceptCookiesForm" action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">
    <?
    foreach($aTabs as $aTab){
        if($aTab["OPTIONS"]){
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
        }
    }

    $tabControl->Buttons();
    ?>

    <input type="submit" name="apply" value="<? echo(Loc::GetMessage("R52_ACCEPTCOOKIES_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save" />
    <input type="submit" name="default" value="<? echo(Loc::GetMessage("R52_ACCEPTCOOKIES_OPTIONS_INPUT_DEFAULT")); ?>" />
    <input type="hidden" id="tabControl_active_tab" name="tabControl_active_tab" value=""/>

    <?=bitrix_sessid_post();?>
</form>

<?
$tabControl->End();
?>

<script>
    BX.ready(() => {
        var colorPickers = document.querySelectorAll("input[name*='color']");
        colorPickers.forEach((input) => {
            var picker = new BX.ColorPicker({
                bindElement: input,
                defaultColor: "",
                onColorSelected: function(color, picker) {
                    input.value = color;
                    input.style.backgroundColor = color;
                }
            })

            input.addEventListener('focus', () => {
                picker.open();
            })

            if (input.value != "") input.style.backgroundColor = input.value;
        })


        const acceptCookiesForm = document.getElementById("acceptCookiesForm");
        if (acceptCookiesForm) {
            acceptCookiesForm.addEventListener('click', (e)=>{
                if(e.target.getAttribute("name") === "default"){
                    e.preventDefault();

                    if (confirm('<?=Loc::GetMessage("R52_ACCEPTCOOKIES_OPTIONS_SAVE_AS_DEFAULT")?>')) {
                        const input = document.createElement("input");
                        input.setAttribute("type","hidden");
                        input.setAttribute("name","default");
                        input.setAttribute("value","true");

                        acceptCookiesForm.insertAdjacentElement("beforeend", input);
                        acceptCookiesForm.submit(); 
                    }
                }
            })
        }
    })
</script>