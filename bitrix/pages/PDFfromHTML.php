<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/.default/mpdf/vendor/autoload.php';
CModule::IncludeModule('iblock');

function findBiblRefLists($currentElementId, $bibleOrRef, $prop)
{
    $arFilter = [
        'IBLOCK_ID' => 22,
        'ACTIVE' => 'Y',
        $prop => $currentElementId
    ];

    $arSelect = ['PROPERTY_UF_BIBLE', 'PROPERTY_UF_REF'];

    $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

    $result = [];

    while ($props = $res->GetNextElement()) {
        $arFields = $props->GetFields();

        $result['BIBLE'] = $arFields['~PROPERTY_UF_BIBLE_VALUE'];
        $result['REF'] = $arFields['~PROPERTY_UF_REF_VALUE'];
    }

    return $result[$bibleOrRef]['TEXT'];
}

function makePDF($id, $lang)
{
    try {
        $res = CIBlockElement::GetByID($id);
        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_CREATE', 'PROPERTY_*', 'DETAIL_TEXT', 'IBLOCK_SECTION_ID', 'EXTERNAL_ID', 'CODE');
        $arFilter = array('ID' => $id, 'ACTIVE' => 'Y');
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
        }
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 25,
            'margin_right' => 20,
            'margin_top' => 35,
            'margin_bottom' => 35,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        if ($lang == 'bel') {
            $prop = 'PROPERTY_UF_URL_BEL';
            $img_templ = 'header_logo.jpg';
            $alt = 'Энцыклопедыя Беларусі';
            $authors = 'Аўтары';
            $lit_label = 'Літаратура';
            $ref_label = 'Спасылкі';
            $url = '';
            $iblock = 16;
        }
        if ($lang == 'ru') {
            $prop = 'PROPERTY_UF_URL_RU';
            $img_templ = 'header_logo_ru.jpg';
            $alt = 'Энциклопедия Беларуси';
            $authors = 'Авторы';
            $lit_label = 'Литература';
            $ref_label = 'Ссылки';
            $url = '';
            $iblock = 15;
        }

        $html = '<div><h2 style="color: #920000;">' . $arProps['ARTICLE_NAME']['VALUE'] . '</h2>';

        $arSelectAuth = array('NAME');

        $arFilterAuth = array('IBLOCK_ID' => $iblock, 'ACTIVE' => 'Y', 'ID' => $arProps['AUTHOR_BASE']['VALUE']);

        $res_auth = CIBlockElement::GetList(array('NAME' => 'ASC'), $arFilterAuth, false, array('nPageSize' => PHP_INT_MAX), $arSelectAuth);

        $aut_items = array();
        while ($obj = $res_auth->GetNextElement()) {
            $arFieldsObj = $obj->GetFields();
            $aut_items[] = $arFieldsObj['NAME'];
        }

        if (count($aut_items) == 1) {
            $html .= '<p style="color: #696969;padding-top: -10px; font-size: 12px;">' . $authors . '<strong>: ' . $aut_items[0] . '</strong></p>';
        } else if (count($aut_items) > 1) {
            $html .= '<p style="color: #696969;padding-top: -10px; font-size: 12px;">' . $authors . '<strong>: ' . implode(', ', $aut_items) . '</strong></p>';
        }

        $html .= '<style>div.literature li p {margin-top: 0px;} div.literature li {font-size: 12px;} a.abbr.author-helper {text-decoration: none; color:#696969;}</style><div class="detail-text">' . $arFields['DETAIL_TEXT'] . '</div>';

        $check_bible = findBiblRefLists($_GET['get_pdf'], 'BIBLE', $prop);

        $check_ref = findBiblRefLists($_GET['get_pdf'], 'REF', $prop);

        if (!empty($check_bible)):
            $html .= '<hr style="margin-top: 24px;"><div class="literature"><h5>' . $lit_label . '</h5>' . $check_bible . '</div>';
        endif;

        if (!empty($check_ref)):
            $html .= '<hr style="margin-top: 24px;"><div class="reference-list"><h5>' . $ref_label . '</h5><div class="ref-text">' . $check_ref . '</div></div>';
        endif;

        $html .= '</div>';
        $mpdf->SetHTMLHeader('
        <div style="padding-top: 20px;">
            <img src="./' . $img_templ . '" alt="' . $alt . '">
        </div>');

        $mpdf->SetHTMLFooter('<div style="font-size: 12px;color: #696969;border-top: 1px solid #696969;padding-bottom:20px;"><div style="float: right; width: 5px; text-align: right;font-size: 12px;margin-top: 2px;padding-top: 2px;">{PAGENO}</div><div style="margin-top: 2px;text-align: left;">{DATE H:i j-m-Y} | <a href="' . $url . '/slovnik/' . $id . '/" style="color: #0000ff;text-decoration: none;">' . $url . '/slovnik/' . $id . '/</a></div><div style="margin-top:4px;padding-top:4px;border-top: 1px solid #696969;">' . $arFields['NAME'] . '</div></div>');
        $mpdf->WriteHTML($html);
        $mpdf->Output($arProps['ARTICLE_NAME']['VALUE'] . '.pdf', 'D');
    } catch (\Mpdf\MpdfException $e) {
        echo $e->getMessage();
    }
}

if ($_GET['get_pdf']) {
    $lang = $_GET['lang'] == 'bel' ? 'bel' : 'ru';
    makePDF($_GET['get_pdf'], $lang);
}
require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
