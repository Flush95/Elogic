<?php

namespace Elogic\StoreLocator\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->scopeConfig->getValue('admin_config_api/general/api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getModuleStatus()
    {
        return $this->scopeConfig->getValue('admin_config_module_status/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
