<?php
declare(strict_types=1);
namespace Elogic\AdminCrud\Model;

use Elogic\AdminCrud\Api\Data\ShopInterface;
use Elogic\AdminCrud\Api\ShopRepositoryInterface;
use Elogic\AdminCrud\Model\ResourceModel\ShopResource;
use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ShopRepository implements ShopRepositoryInterface
{

    /**
     * @var ShopResource
     */
    private ShopResource $shop;
    /**
     * @var ShopFactory
     */
    private ShopFactory $shopFactory;

    public function __construct(
        ShopResource $shop,
        ShopFactory $shopFactory
    ) {
        $this->shop = $shop;
        $this->shopFactory = $shopFactory;
    }

    /**
     * }
     * Get Shop By Id
     * @param int $id
     * @return ShopInterface
     * @throws NoSuchEntityException
     */
    public function getShopById(int $id): ShopInterface
    {
        $shop = $this->shopFactory->create();
        $this->shop->load($shop, $id);

        if (!$shop->getShopId()) {
            throw new NoSuchEntityException(__('Unable to find store with id "%1"', $id));
        }
        return $shop;
    }

    /**
     * @param ShopInterface $shop
     * @return Shop|ShopInterface
     * @throws CouldNotSaveException
     */
    public function saveShop(ShopInterface $shop)
    {
        /** @var Shop $shop */
        try {
            $this->shop->save($shop);
        } catch (Exception | AlreadyExistsException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $shop;
    }

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteShopById(int $id): bool
    {
        return $this->deleteShop($this->getShopById($id));
    }

    /**
     * @param ShopInterface $shop
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteShop(ShopInterface $shop): bool
    {
        /** @var Shop $shop */
        try {
            $this->shop->delete($shop);
        } catch (Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }
        return true;
    }
}
