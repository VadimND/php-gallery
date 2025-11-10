<?

namespace Sh\BannerRules\Rules;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

abstract class Intreface extends \Sh\BannerRules\RulesTable {

    protected function __construct() {
        
    }

    protected $param = array();

    public function setParams($param) {
        $this->param = $param;
    }

    public function getParams() {
        return $this->param;
    }

    /**
     * @return Sh\BannerRules\Rules\Intreface
     */
    abstract public static function getinstance();

    abstract public function getParamKey();

    abstract public function getCheckerName();

    abstract public function validateBanners($bannerIDs);
}
