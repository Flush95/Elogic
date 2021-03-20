<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Api;

use Elogic\StoreLocator\Api\Data\ShopInterface;
use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @api
 */
interface ShopRepositoryInterface
{

    /**
     * Get Shop By Id
     * @param int $id
     * @throws NoSuchEntityException
     * @return ShopInterface
     */
    public function getShopById(int $id): ShopInterface;

    /**
     * Save new shop
     * @param ShopInterface $shop
     * @throws AlreadyExistsException | Exception
     * @return ShopInterface
     */
    public function saveShop(ShopInterface $shop): ShopInterface;

    /**
     * Delete shop by id
     * @param int $id
     * @return bool
     */
    public function deleteShopById(int $id): bool;

    /**
     * Delete shop entity
     * @param ShopInterface $shop
     * @throws NoSuchEntityException
     * @return bool
     */
    public function deleteShop(ShopInterface $shop): bool;
}
