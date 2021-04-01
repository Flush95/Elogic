<?php
declare(strict_types=1);
namespace Elogic\StoreLocator\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ShopProductsInterface extends ExtensibleDataInterface
{

    /**
     * Get Shop Products id
     * @return int
     */
    public function getTableId(): int;

    /**
     * @param int|null $entityId
     * @return mixed
     */
    public function setTableId(?int $entityId): ShopProductsInterface;

    /**
     * Get Shop id
     * @return int
     */
    public function getShopId(): int;

    /**
     * Set Shop id
     * @param int $shopId
     * @return $this
     */
    public function setShopId(int $shopId): ShopProductsInterface;

    /**
     * Get Product id
     * @return int
     */
    public function getProductId(): int;

    /**
     * Set Product id
     * @param int $productId
     * @return $this
     */
    public function setProductId(int $productId): ShopProductsInterface;
}
