<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class AdvancedSearchComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams["PAGE_SIZE"] = intval($arParams["PAGE_SIZE"]) ?: 20;
        $arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]) ?: 3600;
        return $arParams;
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function saveToSession($data)
    {
        $this->startSession();
        $_SESSION['ADVANCED_SEARCH'] = $data;
    }

    private function getFromSession()
    {
        $this->startSession();
        return $_SESSION['ADVANCED_SEARCH'] ?? [];
    }

    private function clearSession()
    {
        $this->startSession();
        unset($_SESSION['ADVANCED_SEARCH']);
    }

    private function endOfWord($str, $num)
    {
        $last_num = intval(substr($num, -1));
        $pre_last_num = intval(substr($num, -2));

        switch ($str) {
            case 'запись':
                if ($last_num > 1 && $last_num < 5) {
                    $str = 'записи';
                } else if ($last_num == 1) {
                    $str = 'запись';
                } else {
                    $str = 'записей';
                }
                if ($pre_last_num > 10 && $pre_last_num < 15) {
                    $str = 'записей';
                }
                break;

            case 'найдено':
                if ($last_num == 1) {
                    $str = 'найдена';
                } else {
                    $str = 'найдено';
                }
                break;

            default:
                $str = ' ';
                break;
        }
        return $str;
    }

    private function getTableSummary($props)
    {
        if (empty($props['TABLE_SUMMARY']['VALUE'])) {
            return '';
        }
        $val = $props['TABLE_SUMMARY']['VALUE'];
        return is_array($val) ? implode(' ', $val) : (string) $val;
    }

    private function getGalleryMatch($props, $needle)
    {
        $galleryDesc = '';
        $match = false;

        if (!empty($props['GALLERY']['DESCRIPTION']) && is_array($props['GALLERY']['DESCRIPTION'])) {
            foreach ($props['GALLERY']['DESCRIPTION'] as $desc) {
                if ($desc === null || $desc === '')
                    continue;
                $descStr = str_replace("\u{00A0}", ' ', (string) $desc);
                $galleryDesc .= ' ' . $descStr;

                if ($needle !== '' && mb_stripos($descStr, $needle) !== false) {
                    $match = true;
                }
            }
            $galleryDesc = trim($galleryDesc);
        }

        return [$galleryDesc, $match];
    }

    private function performSearch($searchParams)
    {
        CModule::IncludeModule('iblock');

        $arSelect = [
            'ID',
            'NAME',
            'DATE_ACTIVE_FROM',
            'DETAIL_PAGE_URL',
            'DETAIL_TEXT',
            'PROPERTY_TABLE_SUMMARY',
            'PROPERTY_GALLERY'
        ];

        $arFilterBase = [
            'IBLOCK_ID' => [1, 4],
            'ACTIVE_DATE' => 'Y',
            'ACTIVE' => 'Y',
            'INCLUDE_SUBSECTIONS' => 'Y',
        ];

        if (!empty($searchParams['drone']) && $searchParams['drone'] === 'personsFilter') {
            $arFilterBase['PROPERTY_IS_PERSON'] = [317];
        }

        if (!empty($searchParams['razdel']) && $searchParams['razdel'] != 0 && $searchParams['razdel'] != 1) {
            $arFilterBase['SECTION_ID'] = $searchParams['razdel'];
        }

        $searchWord = trim((string) ($searchParams['searchword'] ?? ''));
        $SearchRes = [];

        // Поиск по названию
        if (!empty($searchParams['by-fields']) && $searchParams['by-fields'] === 'nazva') {
            $arFilter = $arFilterBase;
            $arFilter['?NAME'] = $searchWord;

            $res = CIBlockElement::GetList(['NAME' => 'ASC'], $arFilter, false, false, $arSelect);
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $props = $ob->GetProperties();

                $SearchRes[$arFields['ID']] = [
                    'NAME' => $arFields['NAME'],
                    'URL' => $arFields['DETAIL_PAGE_URL'],
                    'DETAIL_TEXT' => $arFields['DETAIL_TEXT'],
                    'TABLE_SUMMARY' => $this->getTableSummary($props),
                    'GAL_DESC' => '',
                ];
            }

            $SearchRes = array_values($SearchRes);
        }
        // Поиск по тексту
        elseif (!empty($searchParams['by-fields']) && $searchParams['by-fields'] === 'details') {
            $arFilter = $arFilterBase;
            $arFilter[] = [
                'LOGIC' => 'OR',
                ['?DETAIL_TEXT' => $searchWord],
                ['?PROPERTY_TABLE_SUMMARY' => $searchWord],
            ];

            $res = CIBlockElement::GetList(['NAME' => 'ASC'], $arFilter, false, false, $arSelect);
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $props = $ob->GetProperties();

                $SearchRes[$arFields['ID']] = [
                    'NAME' => $arFields['NAME'],
                    'URL' => $arFields['DETAIL_PAGE_URL'],
                    'DETAIL_TEXT' => $arFields['DETAIL_TEXT'],
                    'TABLE_SUMMARY' => $this->getTableSummary($props),
                    'GAL_DESC' => '',
                ];
            }

            if (empty($SearchRes)) {
                $res = CIBlockElement::GetList(['NAME' => 'ASC'], $arFilterBase, false, false, $arSelect);
                while ($ob = $res->GetNextElement()) {
                    $arFields = $ob->GetFields();
                    $props = $ob->GetProperties();

                    list($galleryDesc, $matchGallery) = $this->getGalleryMatch($props, $searchWord);

                    if ($matchGallery) {
                        $SearchRes[$arFields['ID']] = [
                            'NAME' => $arFields['NAME'],
                            'URL' => $arFields['DETAIL_PAGE_URL'],
                            'DETAIL_TEXT' => $arFields['DETAIL_TEXT'],
                            'TABLE_SUMMARY' => $this->getTableSummary($props),
                            'GAL_DESC' => $galleryDesc,
                        ];
                    }
                }
            }

            $SearchRes = array_values(array_unique($SearchRes, SORT_REGULAR));
        }

        // Фильтрация по регистру
        if (!empty($searchParams['caseoption'])) {
            $pattern = '/' . preg_quote($searchWord, '/') . '/u';

            if ($searchParams['by-fields'] == 'nazva') {
                $SearchRes = array_filter($SearchRes, function ($item) use ($pattern) {
                    return preg_match($pattern, $item['NAME']);
                });
            } else if ($searchParams['by-fields'] == 'details') {
                $SearchRes = array_filter($SearchRes, function ($item) use ($pattern) {
                    return preg_match($pattern, $item['DETAIL_TEXT']);
                });
            }
        }

        // Исключение "Статья в работе"
        if (!empty($searchParams['filterempty'])) {
            $pattern = '/Статья в работе/u';
            $SearchRes = array_filter($SearchRes, function ($item) use ($pattern) {
                return !preg_match($pattern, $item['DETAIL_TEXT']);
            });
        }

        return $SearchRes;
    }

    public function executeComponent()
    {
        $sessionData = $this->getFromSession();
        
        // Обработка POST запроса
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['searchword'])) {
            $searchParams = [
                'searchword' => $_POST['searchword'] ?? '',
                'by-fields' => $_POST['by-fields'] ?? 'nazva',
                'drone' => $_POST['drone'] ?? 'themesFilter',
                'caseoption' => isset($_POST['caseoption']) ? true : false,
                'filterempty' => isset($_POST['filterempty']) ? true : false,
                'razdel' => $_POST['razdel'] ?? 0,
            ];
            
            $this->saveToSession($searchParams);
            $sessionData = $searchParams;
        }

        // Получаем результаты поиска
        $searchResults = [];
        $totalItems = 0;
        $visibleItems = [];
        $navString = '';

        if (!empty($sessionData['searchword'])) {
            $searchResults = $this->performSearch($sessionData);

            // Пагинация
            $pageSize = $this->arParams["PAGE_SIZE"];
            $currentPage = isset($_GET['PAGEN_1']) ? (int) $_GET['PAGEN_1'] : 1;
            $currentPage = max($currentPage, 1);

            $totalItems = count($searchResults);
            $offset = ($currentPage - 1) * $pageSize;
            $visibleItems = array_slice($searchResults, $offset, $pageSize);

            // Навигация
            $navResult = new CDBResult();
            $navResult->InitFromArray($searchResults);
            $navResult->NavStart($pageSize, false);
            $navString = $navResult->GetPageNavStringEx($navComponentObject, 'Результаты', 'round');
        }

        // Передаем данные в шаблон
        $this->arResult = [
            'SEARCH_PARAMS' => $sessionData,
            'SEARCH_WORD' => $sessionData['searchword'] ?? '',
            'BY_FIELDS' => $sessionData['by-fields'] ?? 'nazva',
            'DRONE' => $sessionData['drone'] ?? 'themesFilter',
            'CASE_OPTION' => $sessionData['caseoption'] ?? false,
            'FILTER_EMPTY' => $sessionData['filterempty'] ?? false,
            'RAZDEL' => $sessionData['razdel'] ?? 0,
            'SEARCH_RESULTS' => $searchResults,
            'VISIBLE_ITEMS' => $visibleItems,
            'TOTAL_ITEMS' => $totalItems,
            'NAV_STRING' => $navString,
            'END_OF_WORD_FUNC' => [$this, 'endOfWord']
        ];

        $this->includeComponentTemplate();
    }
}
?>