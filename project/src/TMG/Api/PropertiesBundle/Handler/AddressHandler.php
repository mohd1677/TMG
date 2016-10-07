<?php
/**
 * AddressHandler
 */
namespace TMG\Api\PropertiesBundle\Handler;

use TMG\Api\ApiBundle\Entity\Address;
use TMG\Api\ApiBundle\Handler\ApiHandler;

class AddressHandler extends ApiHandler
{
    /**
     * @param $lat
     * @param $long
     * @param Address $address
     * @return Address
     */
    public function process($lat, $long, Address $address)
    {
        $address->setDistance(
            $this->calculateDistance(
                $lat,
                $long,
                $address->getLatitude(),
                $address->getLongitude()
            )
        );

        return $address;
    }

    /**
     * Returns the distance between two points on a map using both points
     * latitude and longitude.
     *
     * @param $lat
     * @param $long
     * @param $lat2
     * @param $long2
     * @return int
     */
    private function calculateDistance($lat, $long, $lat2, $long2)
    {
        $dlong = $long2 - $long;
        $dlat = $lat2 - $lat;
        $a = (sin($dlat/2))^2 + cos($lat) * cos($lat2 * (sin($dlong/2)))^2;
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $d = 3959 * $c;

        return $d;
    }
}
