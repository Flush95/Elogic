<?php
namespace Elogic\StoreLocator\Helper;

use Magento\Framework\HTTP\Client\Curl;

class Geo
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

    /**
     * @var Data
     */
    private $data;
    /**
     * @var Curl
     */
    private $curl;

    /**
     * Geo constructor.
     * @param Data $data
     * @param Curl $curl
     */
    public function __construct(Data $data, Curl $curl)
    {
        $this->data = $data;
        $this->curl = $curl;
    }

    /**
     * @param $address
     * @return array
     */
    public function getCoordinates($address): array
    {
        $this->curl->post($this->getUrl($address), []);
        $data = ['latitude' => 0.000000, 'longitude' => 0.000000];

        if ($this->curl->getStatus() == 200) {
            $response = json_decode($this->curl->getBody());
            $data['latitude'] = $response->results[0]->geometry->location->lat;
            $data['longitude'] = $response->results[0]->geometry->location->lng;
        }
        return $data;
    }

    /**
     * @param string $address
     * @return string
     */
    private function getUrl(string $address): string
    {
        $formattedAddress = str_replace(' ', '+', $address);
        return self::API_URL . $formattedAddress . '&key=' . $this->data->getApiKey() . '&sensor=false';
    }
}
