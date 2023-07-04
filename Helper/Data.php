<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_CONFIG_ENABLE = 'order/cancel_order/enable';
    const XML_PATH_CONFIG_NOTICE = 'order/cancel_order/notice';
    const XML_PATH_CONFIG_BUTTON = 'order/cancel_order/label';

    /**
     * @param $storeId
     * @return mixed
     */
    public function getConfigFieldEnable($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CONFIG_ENABLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function getConfigFieldNotice($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CONFIG_NOTICE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function getConfigFieldButton($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CONFIG_BUTTON, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
