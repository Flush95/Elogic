<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Api;

use Elogic\StoreLocator\Api\Data\ShopProductsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ShopProductsRepositoryInterface
{

    /**
     * @param int $id
     * @return ShopProductsInterface
     * @throws NoSuchEntityException
     */
    public function getShopProductById(int $id): ShopProductsInterface;

    /**
     * @param ShopProductsInterface $shopProducts
     * @return ShopProductsInterface
     * @throws CouldNotSaveException
     */
    public function saveShopProducts(ShopProductsInterface $shopProducts): ShopProductsInterface;

    /**
     * @param ShopProductsInterface $shopProduct
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteShopProduct(ShopProductsInterface $shopProduct): bool;

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteShopProductById(int $id): bool;
}
