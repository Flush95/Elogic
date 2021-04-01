<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Model;

use Elogic\StoreLocator\Api\Data\ShopProductsInterface;
use Elogic\StoreLocator\Model\ResourceModel\ShopProductsResource;
use Magento\Framework\Model\AbstractModel;

class ShopProducts extends AbstractModel implements ShopProductsInterface
{

    public function _construct()
    {
        $this->_init(ShopProductsResource::class);
    }

    /**
     * Get Shop id
     * @return int
     */
    public function getShopId(): int
    {
        return intval($this->getData('shop_id'));
    }

    /**
     * Set Shop id
     * @param int $shopId
     * @return ShopProductsInterface
     */
    public function setShopId(int $shopId): ShopProductsInterface
    {
        return $this->setData('shop_id', $shopId);
    }

    /**
     * Get Product id
     * @return int
     */
    public function getProductId(): int
    {
        return $this->getData('product_id');
    }

    /**
     * Set Product id
     * @param int $productId
     * @return ShopProductsInterface
     */
    public function setProductId(int $productId): ShopProductsInterface
    {
        return $this->setData('product_id', $productId);
    }

    /**
     * Get Shop Products id
     * @return int
     */
    public function getTableId(): int
    {
        return $this->getData('entity_id');
    }

    /**
     * @param int|null $entityId
     * @return mixed
     */
    public function setTableId(?int $entityId): ShopProductsInterface
    {
        return $this->setData('entity_id', $entityId);
    }
}
