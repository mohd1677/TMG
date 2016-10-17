<?php

namespace TMG\Api\LegacyBundle\Entity;

trait LatitudeLongitudeTrait
{
    /**
     * latitude goes north<->south from -90 to +90
     * @ORM\Column(type="decimal", precision=14, scale=10, nullable=true)
     */
    protected $latitude;

    /**
     * longitude goes east<->west from -180 to +180
     * @ORM\Column(type="decimal", precision=14, scale=10, nullable=true)
     */
    protected $longitude;

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $v
     *
     * @return LatitudeLongitudeTrait
     */
    public function setLatitude($v)
    {
        $this->latitude = $v;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $v
     *
     * @return LatitudeLongitudeTrait
     */
    public function setLongitude($v)
    {
        $this->longitude = $v;

        return $this;
    }
}
