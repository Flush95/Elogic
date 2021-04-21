<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Block\Shop;

use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class Product extends Template
{
    /**
     * @var Template\Context
     */
    private $context;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productLoader;
    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * Product constructor.
     * @param ShopRepositoryInterface $shopRepository
     * @param ProductRepositoryInterface $productLoader
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(ShopRepositoryInterface $shopRepository, ProductRepositoryInterface $productLoader, Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->context = $context;
        $this->productLoader = $productLoader;
        $this->shopRepository = $shopRepository;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getShopsSoldProduct(): array
    {
        $productId = $this->context->getRequest()->getParam('id');
        $currentProduct = $this->productLoader->getById($productId);

        $shopIds = [];
        if (!is_null($currentProduct->getCustomAttribute('product_shops'))) {
            $shopIds = mb_split(',', $currentProduct->getCustomAttribute('product_shops')->getValue());
        }
        $shops = [];

        foreach ($shopIds as $id) {
            $shops[] = $this->shopRepository->getShopById(intval($id));
        }

        return $shops;
    }
}
