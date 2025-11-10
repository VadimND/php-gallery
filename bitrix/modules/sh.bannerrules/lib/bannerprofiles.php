<?

namespace Sh\BannerRules;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BannerProfilesTable extends Main\Entity\DataManager
{

	public static function getTableName()
	{
		return "b_sh_banner_banner_profiles";
	}

	public static function idValidate()
	{
		return array(new Main\Entity\Validator\RegExp("/^\d+$/"), new Main\Entity\Validator\Unique,);
	}

	public static function getMap()
	{
		$map = array();

		$map["ID"] = new Main\Entity\IntegerField("ID", array(
			"primary" => true,
			"title" => Loc::getMessage("SHDBANNERRULES_ID"),
			"validation" => static::idValidate(),
		));

		$map["BANNER_ID"] = new Main\Entity\IntegerField("BANNER_ID", array(
			"title" => Loc::getMessage("SHDBANNERRULES_BANNER_ID"),
			"required" => true,
		));

		$map["PROFILE_ID"] = new Main\Entity\IntegerField("PROFILE_ID", array(
			"title" => Loc::getMessage("SHDBANNERRULES_PROFILE_ID"),
			"required" => true,
		));

		return $map;
	}
}
