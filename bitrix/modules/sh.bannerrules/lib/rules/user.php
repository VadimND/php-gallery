<?

namespace Sh\BannerRules\Rules;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

class UserTable extends LocationGroupTable {

    public function getParamKey() {
        return "USER_ID";
    }

    public static function getMap() {
        $map = parent::getMap();



        $map["VALUE_INT"] = new Main\Entity\IntegerField("VALUE_INT", array(
         "values"     => self::getValues(),
         "required"   => true,
         "title"      => Loc::getMessage("SHDBANNERRULES_USER_ID"),
        ));

        return $map;
    }

    private static $selfObj;

    /**
     * 
     * @return LocationGroup
     */
    public static  function getInstance() {
        return (isset(self::$selfObj) && (self::$selfObj instanceof UserTable)) ? self::$selfObj : self::$selfObj = new UserTable();
    }

    public function getCheckerName() {
        return Loc::getMessage("SHDBANNERRULES_USER_ID");
    }

}
