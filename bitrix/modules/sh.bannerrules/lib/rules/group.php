<?

namespace Sh\BannerRules\Rules;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

class GroupTable extends LocationGroupTable {

    public function getParamKey() {
        return "USER_GROUP";
    }

    public static function getMap() {
        $map = parent::getMap();



        $map["VALUE_INT"] = new Main\Entity\EnumField("VALUE_INT", array(
         "validation" => array('\Sh\BannerRules\RulesTable', "enumValidate"),
         "values"     => self::getValues(),
         "title"      => Loc::getMessage("SHDBANNERRULES_GROUPLIST"),
        ));

        return $map;
    }

    public static function getValues() {


        $dbl = \Bitrix\Main\GroupTable::query()
                ->setSelect(array("ID", "NAME"))
                ->setOrder(array("C_SORT" => "ASC"))
                ->exec();

        $values = array();
        while ($res = $dbl->fetch()) {
            $values[$res["ID"]] = $res["NAME"];
        }

        return $values;
    }

    private static $selfObj;

    /**
     * 
     * @return LocationGroup
     */
    public static  function getInstance() {
        return (isset(self::$selfObj) && (self::$selfObj instanceof GroupTable)) ? self::$selfObj : self::$selfObj = new GroupTable();
    }

    public function getCheckerName() {
        return Loc::getMessage("SHDBANNERRULES_GROUPLIST");
    }

}
