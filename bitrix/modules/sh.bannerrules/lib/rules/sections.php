<?
namespace Sh\BannerRules\Rules;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

class SectionAllTable extends LocationGroupTable {

    public function getParamKey() {
        return "SECTIONS_ALL";
    }

    public static function getMap() {
        $map = parent::getMap();



        $sections = static::getValues();
        if (!empty($sections)) {
            $map["VALUE_INT"] = new Main\Entity\EnumField("VALUE_INT", array(
                "validation" => array('\Sh\BannerRules\RulesTable', "enumValidate"),
                "values"     => $sections,
                "title"      => Loc::getMessage("SHDBANNERRULES_LOCATION_GROUP"),
            ));
        }
        else {
            $map["VALUE_INT"] = new Main\Entity\IntegerField("VALUE_INT", array(
                "title" => Loc::getMessage("SHDBANNERRULES_LOCATION_GROUP"),
            ));
        }


        return $map;
    }

    function getValues() {
        $query = \Bitrix\Iblock\SectionTable::query();

        $dbl = $query->setSelect(array("IBLOCK_NAME" => "IBLOCK.NAME", "NAME", "ID"))
            ->setFilter(array("IBLOCK" => CATALOG_IBLOCK_ID, "ACTIVE" => "Y"))
            ->setOrder(array("LEFT_MARGIN" => "ASC"))
            ->exec();

        $values = array();

        while ($res = $dbl->fetch()) {
            $values[$res["ID"]] = "[{$res["ID"]}] {$res["NAME"]}";
        }

        return $values;
    }

    private static $selfObj;

    /**
     *
     * @return LocationGroup
     */
    public function getInstance() {
        return (isset(self::$selfObj) && (self::$selfObj instanceof SectionAllTable)) ? self::$selfObj : self::$selfObj = new SectionAllTable();
    }

    public function getCheckerName() {
        return 'Раздел каталога';
    }

}
