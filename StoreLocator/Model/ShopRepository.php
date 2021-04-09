<?php
declare(strict_types=1);
namespace Elogic\StoreLocator\Model;

use Elogic\StoreLocator\Api\Data\ShopInterface;
use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Elogic\StoreLocator\Helper\ImageLinkBuilder;
use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\CollectionFactory;
use Elogic\StoreLocator\Model\ResourceModel\ShopResource;
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
    private $shop;
    /**
     * @var ShopFactory
     */
    private $shopFactory;
    /**
     * @var CollectionFactory
     */
    private $shopsFactory;
    /**
     * @var ImageLinkBuilder
     */
    private $linkBuilder;

    /**
     * ShopRepository constructor.
     * @param ShopResource $shop
     * @param ShopFactory $shopFactory
     * @param CollectionFactory $shopsFactory
     * @param ImageLinkBuilder $linkBuilder
     */
    public function __construct(
        ShopResource $shop,
        ShopFactory $shopFactory,
        CollectionFactory $shopsFactory,
        ImageLinkBuilder $linkBuilder
    ) {
        $this->shop = $shop;
        $this->shopFactory = $shopFactory;
        $this->shopsFactory = $shopsFactory;
        $this->linkBuilder = $linkBuilder;
    }

    /**
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
            throw new NoSuchEntityException(__('Unable to find shop with id %1', $id));
        }
        return $shop;
    }

    /**
     * Get Shop by Url_key
     * @param string $url_key
     * @return ShopInterface
     * @throws NoSuchEntityException
     */
    public function getShopByUrlKey(string $url_key): ShopInterface
    {
        $shop = $this->shopFactory->create();
        $this->shop->load($shop, $url_key, 'url_key');
        if (!$shop->getShopId()) {
            throw new NoSuchEntityException(__('Unable to find shop with url_key ' . $url_key));
        }
        $shop->setImgUrl($this->linkBuilder->getImgLink($shop->getImgUrl()));
        return $shop;
    }

    /**
     * @param ShopInterface $shop
     * @return ShopInterface|Shop
     * @throws CouldNotSaveException
     */
    public function saveShop(ShopInterface $shop): ShopInterface
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

    /**
     * @return array|null
     */
    public function getAllShops(): ?array
    {
        $collection = $this->shopsFactory->create();
        /** @var Shop $shop */
        foreach ($collection->getItems() as $shop) {
            $shop->setImgUrl($this->linkBuilder->getImgLink($shop->getImgUrl()));
        }
        return $collection->getData();
    }

}
