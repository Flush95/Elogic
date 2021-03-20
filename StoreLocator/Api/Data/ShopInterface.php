<?php
declare(strict_types=1);
namespace Elogic\StoreLocator\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ShopInterface extends ExtensibleDataInterface
{

    /**
     * @return mixed
     */
    public function getShopId();

    /**
     * @param int|null $id
     * @return ShopInterface
     */
    public function setShopId(?int $id): ShopInterface;

    /**
     * Get shop_name
     * @return string
     */
    public function getShopName(): string;

    /**
     * Set shop_name
     * @param string $shopName
     * @return $this
     */
    public function setShopName(string $shopName): ShopInterface;

    /**
     * Get description
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set description
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): ShopInterface;

    /**
     * Get img_url
     * @return string
     */
    public function getImgUrl();

    /**
     * Set img_url
     * @param string $imgUrl
     * @return $this
     */
    public function setImgUrl(string $imgUrl): ShopInterface;

    /**
     * Get shop_city
     * @return string
     */
    public function getShopCity(): string;

    /**
     * Set shop_city
     * @param string $shopCity
     * @return $this
     */
    public function setShopCity(string $shopCity): ShopInterface;

    /**
     * Get shop_zip
     * @return int
     */
    public function getShopZip(): int;

    /**
     * Set shop_zip
     * @param int $shopZip
     * @return $this
     */
    public function setShopZip(int $shopZip): ShopInterface;

    /**
     * Get shop_state
     * @return string
     */
    public function getShopState(): string;

    /**
     * Set shop_state
     * @param string $shopState
     * @return $this
     */
    public function setShopState(string $shopState): ShopInterface;

    /**
     * Get shop_address
     * @return string
     */
    public function getShopAddress(): string;

    /**
     * Set shop_address
     * @param string $shopAddress
     * @return $this
     */
    public function setShopAddress(string $shopAddress): ShopInterface;

    /**
     * Get work_schedule
     * @return string
     */
    public function getWorkSchedule(): string;

    /**
     * Set work_schedule
     * @param string $workSchedule
     * @return $this
     */
    public function setWorkSchedule(string $workSchedule): ShopInterface;

    /**
     * Get holiday_work_schedule
     * @return string
     */
    public function getHolidayWorkSchedule(): string;

    /**
     * Set holiday_work_schedule
     * @param string $holidayWorkSchedule
     * @return $this
     */
    public function setHolidayWorkSchedule(string $holidayWorkSchedule): ShopInterface;

    /**
     * Get latitude
     * @return float
     */
    public function getLatitude(): float;

    /**
     * Set latitude
     * @param double $latitude
     * @return $this
     */
    public function setLatitude(float $latitude): ShopInterface;

    /**
     * Get longitude
     * @return float
     */
    public function getLongitude(): float;

    /**
     * Set longitude
     * @param double $longitude
     * @return $this
     */
    public function setLongitude(float $longitude): ShopInterface;

}
