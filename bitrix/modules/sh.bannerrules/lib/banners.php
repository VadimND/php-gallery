<?

namespace Sh\BannerRules;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BannersTable extends Main\Entity\DataManager
{

	public static function getTableName()
	{
		return "b_adv_banner";
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

		return $map;
	}
}
