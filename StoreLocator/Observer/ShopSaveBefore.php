<?php
namespace Elogic\StoreLocator\Observer;

use Elogic\StoreLocator\Helper\Geo;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ShopSaveBefore implements ObserverInterface
{

    /**
     * @var Geo
     */
    private Geo $geoCoordinates;

    /**
     * ShopSaveBefore constructor.
     * @param Geo $geoCoordinates
     */
    public function __construct(Geo $geoCoordinates)
    {
        $this->geoCoordinates = $geoCoordinates;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $shop = $observer->getEvent()->getObject();
        if (!$shop) {
            return;
        }

        $latitude = $shop->getLatitude();
        $longitude = $shop->getLongitude();
        $geolocation = null;
        if (empty($latitude || $longitude) || $latitude == 0 || $longitude == 0) {
            $geolocation = $this->geoCoordinates->getCoordinates(
                $shop->getShopState() . '+' .
                $shop->getShopCity() . '+' .
                $shop->getShopAddress()
            );
        }
        if ($shop->isObjectNew() && !is_null($geolocation)) {
            $shop->setLatitude(doubleval($geolocation['latitude']));
            $shop->setLongitude(doubleval($geolocation['longitude']));
        } elseif (!$shop->isObjectNew() && !is_null($geolocation)) {
            $shop->setLatitude(doubleval($geolocation['latitude']));
            $shop->setLongitude(doubleval($geolocation['longitude']));
        }

        if ($shop->getOrigData() != null && sizeof($changes = array_diff_assoc($shop->getData(), $shop->getOrigData())) > 1) {
            if (array_key_exists('shop_address', $changes) ||
                array_key_exists('shop_city', $changes) ||
                array_key_exists('shop_state', $changes)
            ) {
                $geolocation = $this->geoCoordinates->getCoordinates(
                    $shop->getShopState() . '+' .
                    $shop->getShopCity() . '+' .
                    $shop->getShopAddress()
                );
                $shop->setLatitude(floatval($geolocation['latitude']));
                $shop->setLongitude(floatval($geolocation['longitude']));
            }
        }
    }
}
