<?

namespace Sh\BannerRules\Rules;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

class GenderTable extends \Sh\BannerRules\Rules\Intreface {

    public function getParamKey() {
        return "GENDER";
    }

    public static function getMap() {
        $map = parent::getMap();


        $map["VALUE"] = new Main\Entity\EnumField("VALUE", array(
         "validation" => array('\Sh\BannerRules\RulesTable', "enumValidate"),
         "values"     => self::getValues(),
         "title"      => Loc::getMessage("SHDBANNERRULES_GENDER"),
        ));

        return $map;
    }

    protected static function getValues() {
        return array("F" => Loc::getMessage("SHDBANNERRULES_WOMAN"), "M" => Loc::getMessage("SHDBANNERRULES_MAN"));
    }

    private static $selfObj;

    /**
     * 
     * @return GenderTable
     */
    public static function getInstance() {
        return (isset(self::$selfObj) && (self::$selfObj instanceof GenderTable)) ? self::$selfObj : self::$selfObj = new GenderTable();
    }

    public function getCheckerName() {
        return Loc::getMessage("SHDBANNERRULES_GENDER");
    }

    public function validateBanners($bannerIDs) {


        if (empty($bannerIDs)) {
            return array();
        }

        $queryRules = \Sh\BannerRules\BannersTable::query();



        $arFilter = array(
         "=ID" => $bannerIDs
        );


        if (empty($this->param)) {
            $arFilter["!=BANNERRULES.CLASS"] = get_class($this);
        }
        else {
            $arFilter[] = array(
             "LOGIC" => "OR",
             array("=BANNERRULES.VALUE" => $this->param, "=BANNERRULES.CLASS" => get_class($this)),
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
