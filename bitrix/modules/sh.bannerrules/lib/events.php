<?

namespace Sh\BannerRules;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Events
{

	public static function checkBannerParams(&$arFields)
	{


		if (!empty($_REQUEST["ID"])) {

			$oProfileBanners = new BannerProfilesTable();
			$dbl = $oProfileBanners->query()
				->setSelect(array("ID"))
				->setFilter(array("=BANNER_ID" => $_REQUEST["ID"]))
				->exec();

			while ($res = $dbl->fetch()) {
				$oProfileBanners->delete($res["ID"]);
			}
			if ($arFields["AD_TYPE"] == "template") {
				$template = !is_array($arFields["TEMPLATE"]) ? unserialize($arFields["TEMPLATE"]) : $arFields["TEMPLATE"];

				foreach ($template["PROPS"] as $propList) {
					if (!empty($propList["BANNER_PROFLIES"])) {

						foreach ((array) $propList["BANNER_PROFLIES"] as $profileID) {
							$result = $oProfileBanners->add(array("BANNER_ID" => $_REQUEST["ID"], "PROFILE_ID" => $profileID));
						}
					}
				}
			}
		}
	}
}
