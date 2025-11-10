<?

namespace Sh\BannerRules;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class RulesTable extends Main\Entity\DataManager
{

	public static function getTableName()
	{
		return "b_sh_banner_rules";
	}

	public static function idValidate()
	{
		return array(new Main\Entity\Validator\RegExp("/^\d+$/"), new Main\Entity\Validator\Unique,);
	}

	public static function nameValidate()
	{
		return array(new Main\Entity\Validator\RegExp("/^.+$/"));
	}

	public static function enumValidate()
	{
		return array(new Main\Entity\Validator\RegExp("/^\d+$/"));
	}

	public static function getMap()
	{
		$map = array();

		$map["ID"] = new Main\Entity\IntegerField("ID", array(
			"primary" => true,
			"title" => Loc::getMessage("SHDBANNERRULES_ID"),
			"validation" => static::idValidate(),
		));

		$values = array();
		$oChecker = Checker::getInstance();
		/* @var $checker \Sh\BannerRules\Rules\Intreface  */
		foreach ($oChecker->getCheckers() as $checker) {
			$values[get_class($checker)] = $checker->getCheckerName();
		}

		$map["CLASS"] = new Main\Entity\EnumField("CLASS", array(
			"validation" => array('\Sh\BannerRules\RulesTable', "nameValidate"),
			"values" => $values,
			"title" => Loc::getMessage("SHDBANNERRULES_CLASS")
		));

		$values = array();

		$dbl = ProfilesTable::query()
			->setSelect(array("*"))
			->setOrder(array("SORT" => "ASC"))
			->exec();

		while ($res = $dbl->fetch()) {
			$values[$res["ID"]] = $res["NAME"];
		}
		if (!empty($values)) {

			$map["PROFILE_ID"] = new Main\Entity\EnumField("PROFILE_ID", array(
				"title" => Loc::getMessage("SHDBANNERRULES_PROFILE_ID"),
				"validation" => array('\Sh\BannerRules\RulesTable', "enumValidate"),
				"format" => "/^\d+$/",
				"values" => $values,
			));
		} else {

			$map["PROFILE_ID"] = new Main\Entity\IntegerField("PROFILE_ID", array(
				"title" => Loc::getMessage("SHDBANNERRULES_PROFILE_ID"),
				"required" => true,
			));
		}

		return $map;
	}
}
