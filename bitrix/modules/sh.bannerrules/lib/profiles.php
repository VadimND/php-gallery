<?

namespace Sh\BannerRules;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ProfilesTable extends Main\Entity\DataManager
{

	public static function getTableName()
	{
		return "b_sh_banner_profiles";
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

		$map["NAME"] = new Main\Entity\StringField('NAME', array(
			'title' => Loc::getMessage('SHDBANNERRULES_NAME'),
			"required" => true,
		));


		$map[] = new Main\Entity\IntegerField('SORT', array(
			'default_value' => 100,
			'format' => '/^[0-9]{1,11}$/',
			'title' => Loc::getMessage('SHDBANNERRULES_SORT'),
		));

		return $map;
	}
}
