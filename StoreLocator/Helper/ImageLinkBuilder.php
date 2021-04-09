<?php

declare(strict_types=1);

namespace Elogic\StoreLocator\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class ImageLinkBuilder extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * ImageLinkBuilder constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(Context $context, StoreManagerInterface $storeManager)
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }


    /**
     * Build Link For Shop Images
     * @param string $imgName
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImgLink(string $imgName): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'images/' . $imgName;
    }
}
