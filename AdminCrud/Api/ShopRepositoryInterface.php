<?php
declare(strict_types=1);

namespace Elogic\AdminCrud\Api;

use Elogic\AdminCrud\Api\Data\ShopInterface;
use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ShopRepositoryInterface
{

    /**
     * Get Shop By Id
     * @param int $id
     * @throws NoSuchEntityException
     */
    public function getShopById(int $id);

    /**
     * Save new shop
     * @param ShopInterface $shop
     * @throws AlreadyExistsException | Exception
     */
    public function saveShop(ShopInterface $shop);

    /**
     * Delete shop by id
     * @param int $id
     */
    public function deleteShopById(int $id);

    /**
     * Delete shop entity
     * @param ShopInterface $shop
     * @throws NoSuchEntityException
     */
    public function deleteShop(ShopInterface $shop);
}
