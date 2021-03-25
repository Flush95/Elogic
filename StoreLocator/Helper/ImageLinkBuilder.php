<?php

declare(strict_types=1);

namespace Elogic\StoreLocator\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\UrlInterface;

class ImageLinkBuilder extends AbstractHelper
{

    /**
     * Build Link For Shop Images
     * @param string $imgName
     * @return string
     */
    public function getImgLink(string $imgName): string
    {
        $storeManager = ObjectManager::getInstance()->create('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'images/' . $imgName;
    }
}
