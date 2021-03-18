<?php

declare(strict_types=1);

namespace Elogic\StoreLocator\Block\Index;

use Elogic\AdminCrud\Model\ShopRepository;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class Index extends Template
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * CRUD constructor.
     * @param Template\Context $context
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(Template\Context $context, StoreManagerInterface $storeManager, array $data = [])
    {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
    }

    public function getAllShops()
    {
        $objectManager = ObjectManager::getInstance();
        $collection = $objectManager->create('Elogic\AdminCrud\Model\ResourceModel\ShopCollections\Collection');
        return $collection;
    }



    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImagesUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'images/';
    }

    public function getApiKey(): string
    {
        return ObjectManager::getInstance()->create('Elogic\AdminConfig\Helper\Data')->getApiKey();
    }
}
