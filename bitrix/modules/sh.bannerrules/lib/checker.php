<?

namespace Sh\BannerRules;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Checker
{

	protected $checkerList = array();

	private function __construct()
	{
		$this->addChecker(\Sh\BannerRules\Rules\LocationGroupTable::getInstance());

		$this->addChecker(\Sh\BannerRules\Rules\GenderTable::getInstance());

		$this->addChecker(\Sh\BannerRules\Rules\SectionTable::getInstance());

		$this->addChecker(\Sh\BannerRules\Rules\AuthStatusTable::getInstance());

		$this->addChecker(\Sh\BannerRules\Rules\GroupTable::getInstance());

		$this->addChecker(\Sh\BannerRules\Rules\UserTable::getInstance());
	}

	public function addChecker(\Sh\BannerRules\Rules\Intreface $checker)
	{
		$this->checkerList[get_class($checker)] = $checker;
	}

	public function getCheckers()
	{
		return $this->checkerList;
	}

	private static $selfObj;

	/**
	 * 
	 * @return Checker
	 */
	public static  function getInstance()
	{
		return (isset(self::$selfObj) && (self::$selfObj instanceof Checker)) ? self::$selfObj : self::$selfObj = new Checker();
	}

	public function resetParams()
	{
		/* @var $checker \Sh\BannerRules\Rules\Intreface  */
		foreach ($this->checkerList as $checker) {
			$this->setParam($checker->getParamKey(), array());
		}
		return $this;
	}

	/**
	 * SetParams
	 * @param type $paramName
	 * @param type $paramValue
	 */
	public function setParam($paramName, $paramValue = array())
	{

		if (!is_string($paramName) || !strlen($paramName)) {
			return $this;
		}

		/* @var $checker \Sh\BannerRules\Rules\Intreface  */
		foreach ($this->checkerList as $checker) {
			if ($checker->getParamKey() == $paramName) {

				$checker->setParams($paramValue);
			}
		}
		return $this;
	}

	function check($bannerList)
	{

		/* @var $checker \Sh\BannerRules\Rules\Intreface  */
		foreach ($this->checkerList as $checker) {

			$bannerList = $checker->validateBanners($bannerList);
		}

		return $bannerList;
	}

	public function GetCurrentParams()
	{

		$params = array();

		/* @var $checker \Sh\BannerRules\Rules\Intreface  */
		foreach ($this->checkerList as $checker) {
			$params[$checker->getParamKey()] = $checker->getParams();
		}

		return $params;
	}
}
