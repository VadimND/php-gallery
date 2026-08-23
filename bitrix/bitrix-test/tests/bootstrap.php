<?php

/**
 * Лёгкая эмуляция части Bitrix API для запуска логики каталога вне
 * полноценного ядра Bitrix (быстрый локальный прогон).
 *
 * Эмуляция воспроизводит поведение реального Bitrix в тех аспектах, которые
 * нужны компоненту: множественные свойства, CIBlockElement::GetProperty,
 * CIBlockElement::GetList, ORM ElementTable::getList.
 *
 * ЭТОТ ФАЙЛ МЕНЯТЬ НЕЛЬЗЯ — ваш код должен работать поверх него как есть.
 */

// ------- Эмуляция Bitrix\Main\Loader ------------------------------------
namespace Bitrix\Main {
    class Loader
    {
        public static function includeModule($name) { return true; }
        public static function registerAutoLoadClasses($module, $classes) {}
    }

    class Application
    {
        private static $instance;
        public static function getInstance() { return self::$instance ?: self::$instance = new self(); }
        public function getContext() { return new Context(); }
    }

    class Context
    {
        public function getRequest() { return new Request(); }
    }

    class Request
    {
        public function get($name)
        {
            return $_GET[$name] ?? null;
        }
    }
}

// ------- Эмуляция ORM ElementTable --------------------------------------
namespace Bitrix\Iblock {
    class DbResult
    {
        private $rows;
        private $i = 0;
        public function __construct(array $rows) { $this->rows = array_values($rows); }
        public function fetch()
        {
            if ($this->i >= count($this->rows)) return false;
            return $this->rows[$this->i++];
        }
    }

    class ElementTable
    {
        public static function getList(array $params)
        {
            $GLOBALS['__QUERY_COUNT']++;

            $rows = array_values($GLOBALS['__DB']['elements']);

            // Простейшая обработка filter
            if (!empty($params['filter'])) {
                foreach ($params['filter'] as $key => $value) {
                    $field = ltrim($key, '=');
                    $rows = array_filter($rows, function ($r) use ($field, $value) {
                        return isset($r[$field]) && (string)$r[$field] === (string)$value;
                    });
                }
            }

            // select
            if (!empty($params['select'])) {
                $sel = $params['select'];
                $rows = array_map(function ($r) use ($sel) {
                    $out = [];
                    foreach ($sel as $f) {
                        $out[$f] = $r[$f] ?? null;
                    }
                    return $out;
                }, $rows);
            }

            return new DbResult(array_values($rows));
        }
    }

    class ElementPropertyTable {}
}

// ------- Эмуляция CIBlockElement::GetProperty ---------------------------
namespace {

    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

    $GLOBALS['__DB'] = [
        'elements'   => [],
        'properties' => [],
    ];
    $GLOBALS['__QUERY_COUNT'] = 0;

    require __DIR__ . '/fixtures.php';

    class CPropResult
    {
        private $rows;
        private $i = 0;
        public function __construct(array $rows) { $this->rows = array_values($rows); }
        public function Fetch()
        {
            if ($this->i >= count($this->rows)) return false;
            return $this->rows[$this->i++];
        }
    }

    class CIBlockElementListResult
    {
        private $rows; private $i = 0;
        public function __construct(array $rows) { $this->rows = array_values($rows); }
        public function Fetch()
        {
            if ($this->i >= count($this->rows)) return false;
            return $this->rows[$this->i++];
        }
        public function GetNext() { return $this->Fetch(); }
    }

    class CIBlockElement
    {
        /**
         * Эмуляция CIBlockElement::GetList — как в реальном Bitrix, поддерживает
         * получение множества элементов и их свойств одним вызовом.
         * Поддерживает filter['ID' => array], select с 'PROPERTY_CODE'.
         */
        public static function GetList($order, $filter, $groupBy = false, $navParams = false, $select = [])
        {
            $GLOBALS['__QUERY_COUNT']++;

            $ids = [];
            if (isset($filter['ID'])) {
                $ids = is_array($filter['ID']) ? $filter['ID'] : [$filter['ID']];
            } else {
                $ids = array_keys($GLOBALS['__DB']['elements']);
            }
            $iblockId = $filter['IBLOCK_ID'] ?? null;

            $rows = [];
            foreach ($ids as $id) {
                $id = (int)$id;
                if (!isset($GLOBALS['__DB']['elements'][$id])) continue;
                $el = $GLOBALS['__DB']['elements'][$id];
                if ($iblockId !== null && (int)$el['IBLOCK_ID'] !== (int)$iblockId) continue;

                $row = $el;
                foreach ($select as $field) {
                    if (strpos($field, 'PROPERTY_') === 0) {
                        $code = substr($field, strlen('PROPERTY_'));
                        $key = $id . ':' . $code;
                        $vals = $GLOBALS['__DB']['properties'][$key] ?? [null];
                        // Bitrix отдаёт PROPERTY_CODE_VALUE
                        $row['PROPERTY_' . $code . '_VALUE'] = $vals[0] ?? null;
                    }
                }
                $rows[] = $row;
            }
            return new CIBlockElementListResult($rows);
        }

        public static function GetProperty($iblockId, $elementId, $order, $filter)
        {
            $GLOBALS['__QUERY_COUNT']++;

            $code = $filter['CODE'] ?? null;
            $key = $elementId . ':' . $code;

            $rows = [];
            if (isset($GLOBALS['__DB']['properties'][$key])) {
                foreach ($GLOBALS['__DB']['properties'][$key] as $v) {
                    $rows[] = ['VALUE' => $v];
                }
            }
            if (empty($rows)) {
                // Bitrix возвращает строку с пустым VALUE для существующего свойства
                $rows[] = ['VALUE' => null];
            }

            return new CPropResult($rows);
        }
    }

    // Заглушки для компонента/шаблона (не нужны для юнит-тестов логики)
    if (!function_exists('ShowError')) {
        function ShowError($m) { echo "ERROR: $m\n"; }
    }

    define('B_PROLOG_INCLUDED', true);
}
