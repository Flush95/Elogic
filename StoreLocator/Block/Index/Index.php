<?php

declare(strict_types=1);

namespace Elogic\StoreLocator\Block\Index;

use Elogic\StoreLocator\Helper\ImageLinkBuilder;
use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Pager;

class Index extends Template
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ImageLinkBuilder
     */
    public $linkBuilder;

    /**
     * CRUD constructor.
     * @param ImageLinkBuilder $linkBuilder
     * @param CollectionFactory $collectionFactory
     * @param Template\Context $context
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(ImageLinkBuilder $linkBuilder, CollectionFactory $collectionFactory, Template\Context $context, StoreManagerInterface $storeManager, array $data = [])
    {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->collectionFactory = $collectionFactory;
        $this->linkBuilder = $linkBuilder;
    }


    /**
     * @return $this
     */
    protected function _prepareLayout(): Index
    {
        parent::_prepareLayout();
        if ($this->getAllShops()) {
            try {
                /** @var Pager $pager */
                $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager');
                $pager->setAvailableLimit([3 => 3]);
                $pager->setShowPerPage(true);
                $pager->setPageVarName("current_page");
                $pager->setCollection($this->getAllShops());
                $this->setChild('pager', $pager);
                $this->getAllShops()->load();
            } catch (LocalizedException $e) {
                //
            }
        }
        return $this;
    }

    public function getAllShops()
    {
        $page = $this->getRequest()->getParam('current_page') ? $this->getRequest()->getParam('current_page') : 1;
        $searchValue = $this->getRequest()->getParam('search');
        $collection = ObjectManager::getInstance()->create('Elogic\StoreLocator\Model\ResourceModel\ShopCollections\Collection');

        if (!is_null($searchValue)) {
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
