<?

namespace Sh\BannerRules\Rules;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

class LocationGroupTable extends \Sh\BannerRules\Rules\Intreface {

    public function getParamKey() {
        return "LOCATION_GROUP";
    }

    public static function getMap() {
        $map = parent::getMap();


        $locations = self::getValues();

        if (!empty($locations)) {
            $map["VALUE_INT"] = new Main\Entity\EnumField("VALUE_INT", array(
             "validation" => array('\Sh\BannerRules\RulesTable', "enumValidate"),
             "values"     => $locations,
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

    public static function getValues() {
        $dbl = \Bitrix\Sale\Location\GroupTable::query()
                ->setSelect(array("ID", "NAME_RU" => "NAME.NAME", "SORT"))
                ->setOrder(array("SORT" => "ASC"))
                ->setGroup(array("ID"))
                ->exec();

        $values = array();
        while ($res = $dbl->fetch()) {
            $values[$res["ID"]] = $res["NAME_RU"];
        }

        return $values;
    }

    private static $selfObj;

    /**
     * 
     * @return LocationGroup
     */
    public static function getInstance() {
        return (isset(self::$selfObj) && (self::$selfObj instanceof LocationGroupTable)) ? self::$selfObj : self::$selfObj = new LocationGroupTable();
    }

    public function getCheckerName() {
        return Loc::getMessage("SHDBANNERRULES_LOCATION_GROUP");
    }

    public function validateBanners($bannerIDs) {


        if (empty($bannerIDs)) {
            return array();
        }

        $queryRules = \Sh\BannerRules\BannersTable::query();


        $arFilter = array(
         "=ID" => $bannerIDs,
        );


        if (empty($this->param)) {
            $arFilter["!=BANNERRULES.CLASS"] = get_class($this);
        }
        else {
            $arFilter[] = array(
             "LOGIC" => "OR",
             array("=BANNERRULES.VALUE_INT" => $this->param, "=BANNERRULES.CLASS" => get_class($this)),
             array("!=BANNERRULES.CLASS" => get_class($this))
            );
        }


        $dbl = $queryRules
                ->registerRuntimeField("BANNERTOPROFILES", array(
                 "data_type" => "\Sh\BannerRules\BannerProfilesTable",
                 'reference' => array('=ref.BANNER_ID' => 'this.ID'),
                 'join_type' => "LEFT"
                        )
                )
                ->registerRuntimeField("BANNERRULES", array(
                 "data_type" => get_class($this),
                 'reference' => array('=ref.PROFILE_ID' => 'this.BANNERTOPROFILES.PROFILE_ID'),
                 'join_type' => "LEFT"
                        )
                )
                ->setSelect(array("ID"))
                ->setFilter($arFilter)
                ->exec();


        $bannerIDs = array();
        while ($res = $dbl->fetch()) {
            $bannerIDs[$res["ID"]] = $res["ID"];
        }

        return $bannerIDs;
    }

}
