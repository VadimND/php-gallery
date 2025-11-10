<?

namespace Sh\BannerRules\Rules;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

class SectionTable extends LocationGroupTable
{

    public function getParamKey()
    {
        return "SECTIONS";
    }

    public static function getMap()
    {
        $map = parent::getMap();



        $sections = self::getValues();
        if (!empty($sections)) {
            $map["VALUE_INT"] = new Main\Entity\EnumField("VALUE_INT", array(
                "validation" => array('\Sh\BannerRules\RulesTable', "enumValidate"),
                "values" => $sections,
                "title" => Loc::getMessage("SHDBANNERRULES_LOCATION_GROUP"),
            ));
        } else {
            $map["VALUE_INT"] = new Main\Entity\IntegerField("VALUE_INT", array(
                "title" => Loc::getMessage("SHDBANNERRULES_LOCATION_GROUP"),
            ));
        }


        return $map;
    }

    static function getNameChain($setSectionID, $sections, $menuParent)
    {
        if (!$setSectionID) {
            return [];
        }


        $currentSection = $sections[$menuParent[$setSectionID]][$setSectionID];

        $result = self::getNameChain($menuParent[$setSectionID], $sections, $menuParent);
        $result[] = $currentSection['NAME'];

        return $result;
    }

    public static function getValues()
    {
        $query = \Bitrix\Iblock\SectionTable::query();

        $query->registerRuntimeField("IBLOCK", array(
                "data_type" => "\Bitrix\Iblock\IblockTable",
                'reference' => array('=ref.ID' => 'this.IBLOCK_ID'),
                'join_type' => "INNER"
                )
            )->setSelect(array("IBLOCK_NAME" => "IBLOCK.NAME", "NAME", "ID", 'IBLOCK_SECTION_ID'))
            ->setFilter(array("DEPTH_LEVEL" => 1,))
            ->setOrder(array("IBLOCK_ID" => "ASC", 'LEFT_MARGIN' => 'ASC'));

        if (defined('CATALOG_IBLOCK_ID')) {
            $query->addFilter('=IBLOCK_ID', CATALOG_IBLOCK_ID);
            $query->addFilter('DEPTH_LEVEL', [1, 2, 3]);
            $query->setSelect(array("NAME", "ID", 'IBLOCK_SECTION_ID'));
        }

        $dbl = $query->exec();


        $sections = $menuParent = $notActive = $values = [];

        while ($res = $dbl->fetch()) {
            $values[$res['ID']] = $res;

            $sections[intval($res['IBLOCK_SECTION_ID'])][$res['ID']] = $res;
            $menuParent[$res['ID']] = intval($res['IBLOCK_SECTION_ID']);
        }


        foreach ($values as &$section) {
            $section = '[' . $section['ID'] . '] ' . implode(' -> ', self::getNameChain($section['ID'], $sections, $menuParent));
        }

        unset($section);

        return $values;
    }

    private static $selfObj;

    /**
     * 
     * @return LocationGroup
     */
    public static function getInstance()
    {
        return (isset(self::$selfObj) && (self::$selfObj instanceof SectionTable)) ? self::$selfObj : self::$selfObj = new SectionTable();
    }

    public function getCheckerName()
    {
        return Loc::getMessage("SHDBANNERRULES_SECTION");
    }
}
