<?php

declare(strict_types=1);

namespace Elogic\StoreLocator\Block\Index;

use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\Collection;
use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
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
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * CRUD constructor.
     * @param CollectionFactory $collectionFactory
     * @param Template\Context $context
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(CollectionFactory $collectionFactory, Template\Context $context, StoreManagerInterface $storeManager, array $data = [])
    {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImagesUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'images/';
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return ObjectManager::getInstance()->create('Elogic\StoreLocator\Helper\Data')->getApiKey();
    }

    /**
     * @return $this
     */
    protected function _prepareLayout(): Index
    {
        parent::_prepareLayout();
        if ($this->getAllShops()) {
            try {
                $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager')
                    ->setAvailableLimit([3 => 3])
                    ->setShowPerPage(true)
                    ->setCollection($this->getAllShops());
                $this->setChild('pager', $pager);
                $this->getAllShops()->load();
            } catch (LocalizedException $e) {
                //
            }
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getAllShops(): Collection
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $searchValue = $this->getRequest()->getParam('search');

        /** @var Collection $collection */
        $collection = ObjectManager::getInstance()->create('Elogic\StoreLocator\Model\ResourceModel\ShopCollections\Collection');
        if (!is_null($searchValue)) {
            //$collection->addFieldToFilter('shop_name', $searchValue);
            $collection->addFieldToFilter(
                'shop_name',
                ['like' => '%' . $searchValue . '%'],
            );
        }
        $collection->setOrder('shop_id', 'ASC');
        $collection->setPageSize(3);
        $collection->setCurPage($page);
        return $collection;
    }

    public function getPagerHtml(): string
    {
        return $this->getChildHtml('pager');
    }

}
