<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Model;

use Elogic\StoreLocator\Api\Data\ShopProductsInterface;
use Elogic\StoreLocator\Model\ResourceModel\ShopProductsResource;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ShopProductsRepository
{

    /**
     * @var ShopProductsInterface
     */
    private $shopProducts;
    /**
     * @var ShopProductsResource
     */
    private $productsResource;

    /**
     * ShopProductsRepository constructor.
     * @param ShopProductsInterface $shopProducts
     * @param ShopProductsResource $productsResource
     */
    public function __construct(ShopProductsInterface $shopProducts, ShopProductsResource $productsResource)
    {
        $this->shopProducts = $shopProducts;
        $this->productsResource = $productsResource;
    }

    /**
     * @param int $id
     * @return ShopProducts
     * @throws NoSuchEntityException
     */
    public function getShopProductById(int $id): ShopProducts
    {
        $shopProducts = $this->productsResource->create();
        $this->productsResource->load($shopProducts, $id);

        if (!$shopProducts->getTableId()) {
            throw new NoSuchEntityException(__('Unable to find shop product with id %1', $id));
        }
        return $shopProducts;
    }

    /**
     * @param ShopProductsInterface $shopProducts
     * @return ShopProducts
     * @throws CouldNotSaveException
     */
    public function saveShopProducts(ShopProductsInterface $shopProducts): ShopProducts
    {
        /** @var ShopProducts $shopProducts */
        try {
            $this->productsResource->save($shopProducts);
        } catch (AlreadyExistsException | \Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $shopProducts;
    }

    /**
     * @param ShopProductsInterface $shopProduct
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteShopProduct(ShopProductsInterface $shopProduct): bool
    {
        try {
            /** @var ShopProducts $shopProduct */
            $this->productsResource->delete($shopProduct);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete this shop product'));
        }
        return true;
    }

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteShopProductById(int $id): bool
    {
        return $this->deleteShopProduct($this->getShopProductById($id));
    }
}
