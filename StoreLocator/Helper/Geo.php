<?php
namespace Elogic\StoreLocator\Helper;

class Geo
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

    /**
     * @var Data
     */
    private Data $data;

    /**
     * Geo constructor.
     * @param Data $data
     */
    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    /**
     * @param $address
     * @return array
     */
    public function getCoordinates($address): array
    {
        $formattedAddr = str_replace(' ', '+', $address);
        $geocodeFromAddress = file_get_contents(
            self::API_URL .
            $formattedAddr .
            '&key=' . $this->data->getApiKey() .
            '&sensor=false'
        );

        $output = json_decode($geocodeFromAddress);

        $data = ['latitude' => 0.000000, 'longitude' => 0.000000];
        if ($output->status == 'OK') {
            $data['latitude'] = $output->results[0]->geometry->location->lat;
            $data['longitude'] = $output->results[0]->geometry->location->lng;
        }
        return $data;
    }
}
