<?

namespace Sh\BannerRules\Rules;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

class AuthStatusTable extends LocationGroupTable {

    public function getParamKey() {
        return "AUTHSTATUS";
    }

    public static function getMap() {
        $map = parent::getMap();



        $map["VALUE_INT"] = new Main\Entity\EnumField("VALUE_INT", array(
         "validation" => array('\Sh\BannerRules\RulesTable', "enumValidate"),
         "values"     => self::getValues(),
         "title"      => Loc::getMessage("SHDBANNERRULES_AUTHSTATUS"),
        ));

        return $map;
    }

    public static function getValues() {


        return array(
         "0" => Loc::getMessage("SHDBANNERRULES_AUTHSTATUS_NO"),
         "1" => Loc::getMessage("SHDBANNERRULES_AUTHSTATUS_YES"),
        );
    }

    private static $selfObj;

    /**
     * 
     * @return LocationGroup
     */
    public static function getInstance() {
        return (isset(self::$selfObj) && (self::$selfObj instanceof AuthStatusTable)) ? self::$selfObj : self::$selfObj = new AuthStatusTable();
    }

    public function getCheckerName() {
        return Loc::getMessage("SHDBANNERRULES_AUTHSTATUS");
    }

}
