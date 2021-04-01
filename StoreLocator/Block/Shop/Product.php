<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Block\Shop;

use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\ShopProductsCollectionFactory;
use Elogic\StoreLocator\Model\ShopRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class Product extends Template
{
    /**
     * @var Template\Context
     */
    private Template\Context $context;
    /**
     * @var ShopProductsCollectionFactory
     */
    private ShopProductsCollectionFactory $collectionFactory;
    /**
     * @var ShopRepository
     */
    private ShopRepository $shopRepository;

    /**
     * Product constructor.
     * @param ShopProductsCollectionFactory $collectionFactory
     * @param ShopRepository $shopRepository
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(ShopProductsCollectionFactory $collectionFactory, ShopRepository $shopRepository, Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->context = $context;
        $this->collectionFactory = $collectionFactory;
        $this->shopRepository = $shopRepository;
    }

    public function getShopsSoldProduct(): array
    {
        $productId = $this->context->getRequest()->getParam('id');

        $productsCollection = $this->collectionFactory->create()->addFieldToFilter('product_id', $productId)->load();

        $shops = [];
        foreach ($productsCollection as $product) {
            try {
                $shops[] = $this->shopRepository->getShopById(intval($product->getShopId()));
            } catch (NoSuchEntityException $e) {
                var_dump($e);
            }
        }
        return $shops;
    }
}
