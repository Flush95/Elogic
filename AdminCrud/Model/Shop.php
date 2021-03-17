<?php

namespace Elogic\AdminCrud\Model;

use Elogic\AdminCrud\Model\ResourceModel\ShopResource;
use Elogic\AdminCrud\Api\Data\ShopInterface;
use Magento\Framework\Model\AbstractModel;

class Shop extends AbstractModel implements ShopInterface
{
    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'shop';

    protected function _construct()
    {
        $this->_init(ShopResource::class);
    }

    /**
     * @return int|null
     */
    public function getShopId(): ?int
    {
        return $this->getData('shop_id');
    }


    /**
     * @param int|null $id
     * @return ShopInterface
     */
    public function setShopId(?int $id): ShopInterface
    {
        return $this->setData('shop_id', $id);
    }

    /**
     * @return string
     */
    public function getShopName(): string
    {
        return $this->getData('shop_name');
    }

    /**
     * @param string $shopName
     * @return ShopInterface
     */
    public function setShopName(string $shopName): ShopInterface
    {
        return $this->setData('shop_name', $shopName);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData('description');
    }

    /**
     * @param string $description
     * @return ShopInterface
     */
    public function setDescription(string $description): ShopInterface
    {
        return $this->setData('description', $description);
    }


    public function getImgUrl()
    {
        return $this->getData('img_url');
    }

    /**
     * @param string $imgUrl
     * @return ShopInterface
     */
    public function setImgUrl(string $imgUrl): ShopInterface
    {
        return $this->setData('img_url', $imgUrl);
    }

    /**
     * @return string
     */
    public function getShopCity(): string
    {
        return $this->getData('shop_city');
    }

    /**
     * @param string $shopCity
     * @return ShopInterface
     */
    public function setShopCity(string $shopCity): ShopInterface
    {
        return $this->setData('shop_city', $shopCity);
    }

    /**
     * @return int
     */
    public function getShopZip(): int
    {
        return $this->getData('shop_zip');
    }

    /**
     * @param int $shopZip
     * @return ShopInterface
     */
    public function setShopZip(int $shopZip): ShopInterface
    {
        return $this->setData('shop_zip', $shopZip);
    }

    /**
     * @return string
     */
    public function getShopState(): string
    {
        return $this->getData('shop_state');
    }

    /**
     * @param string $shopState
     * @return ShopInterface
     */
    public function setShopState(string $shopState): ShopInterface
    {
        return $this->setData('shop_state', $shopState);
    }

    /**
     * @return string
     */
    public function getShopAddress(): string
    {
        return $this->getData('shop_address');
    }

    /**
     * @param string $shopAddress
     * @return ShopInterface
     */
    public function setShopAddress(string $shopAddress): ShopInterface
    {
        return $this->setData('shop_address', $shopAddress);
    }

    /**
     * @return string
     */
    public function getWorkSchedule(): string
    {
        return $this->getData('work_schedule');
    }

    /**
     * @param string $workSchedule
     * @return ShopInterface
     */
    public function setWorkSchedule(string $workSchedule): ShopInterface
    {
        return $this->setData('work_schedule', $workSchedule);
    }

    /**
     * @return string
     */
    public function getHolidayWorkSchedule(): string
    {
        return $this->getData('holiday_work_schedule');
    }

    /**
     * @param string $holidayWorkSchedule
     * @return ShopInterface
     */
    public function setHolidayWorkSchedule(string $holidayWorkSchedule): ShopInterface
    {
        return $this->setData('holiday_work_schedule');
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return (float) $this->getData('latitude');
    }

    /**
     * @param $latitude
     * @return ShopInterface
     */
    public function setLatitude($latitude): ShopInterface
    {
        return $this->setData('latitude', $latitude);
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return (float) $this->getData('longitude');
    }

    /**
     * @param float $longitude
     * @return ShopInterface
     */
    public function setLongitude(float $longitude): ShopInterface
    {
        return $this->setData('longitude', $longitude);
    }

}
