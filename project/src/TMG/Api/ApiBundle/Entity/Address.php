<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Address
 *
 * @ORM\Table(name="Addresses")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\AddressRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Address
{
    /**
     * Fields that are required when creating a new Property.
     * @var array
     */
    public static $requiredPostFields = [
        "line_1" => true,
        "line_2" => false,
        "city" => true,
        "latitude" => false,
        "longitude" => false,
        "interstate_number" => false,
        "interstate_exit" => false,
        "display_interstate_exit" => false
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="line_1", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $line1;

    /**
     * @var string
     *
     * @ORM\Column(name="line_2", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $line2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $city;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State", cascade={"persist"})
     * @ORM\JoinColumn(name="state", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $state;

    /**
     * @var postalCode
     *
     * @ORM\ManyToOne(targetEntity="PostalCode", cascade={"persist"})
     * @ORM\JoinColumn(name="postal_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $postalCode;

    /**
     * @var country
     *
     * @ORM\ManyToOne(targetEntity="Country", cascade={"persist"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", precision=14, scale=10, nullable=true)
     *
     * @Serializer\Expose
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=14, scale=10, nullable=true)
     *
     * @Serializer\Expose
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="interstate_number", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $interstateNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="interstate_exit", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $interstateExit;

    /**
     * @var string
     *
     * @ORM\Column(name="display_interstate_exit", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $displayInterstateExit;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, unique=true, nullable=true)
     */
    private $hash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var number
     *
     * @Serializer\Expose
     */
    private $distance;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @param $distance
     * @return $this
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @return number
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set line1
     *
     * @param string $line1
     * @return Address
     */
    public function setLine1($line1)
    {
        $this->line1 = $line1;

        return $this;
    }

    /**
     * Get line1
     *
     * @return string
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * Set line2
     *
     * @param string $line2
     * @return Address
     */
    public function setLine2($line2)
    {
        $this->line2 = $line2;

        return $this;
    }

    /**
     * Get line2
     *
     * @return string
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param State $state
     * @return Address
     */
    public function setState(State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set postalCode
     *
     * @param PostalCode $postalCode
     * @return Address
     */
    public function setPostalCode(PostalCode $postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return Address
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Address
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Address
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set interstateNumber
     *
     * @param string $interstateNumber
     * @return Address
     */
    public function setInterstateNumber($interstateNumber)
    {
        $this->interstateNumber = $interstateNumber;

        return $this;
    }

    /**
     * Get interstateNumber
     *
     * @return string
     */
    public function getInterstateNumber()
    {
        return $this->interstateNumber;
    }

    /**
     * Set interstateExit
     *
     * @param string $interstateExit
     * @return Address
     */
    public function setInterstateExit($interstateExit)
    {
        $this->interstateExit = $interstateExit;

        return $this;
    }

    /**
     * Get interstateExit
     *
     * @return string
     */
    public function getInterstateExit()
    {
        return $this->interstateExit;
    }

    /**
     * Set displayInterstateExit
     *
     * @param string $displayInterstateExit
     * @return Address
     */
    public function setDisplayInterstateExit($displayInterstateExit)
    {
        $this->displayInterstateExit = $displayInterstateExit;

        return $this;
    }

    /**
     * Get displayInterstateExit
     *
     * @return string
     */
    public function getDisplayInterstateExit()
    {
        return $this->displayInterstateExit;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Address
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Update hash
     *
     * @return string
     */
    public function updateHash()
    {
        return $this->hash = $this->generateHash();
    }

    /**
     * Generate hash
     *
     * @return string
     */

    public function generateHash()
    {
        $components = [
            $this->line1,
            $this->line2,
            $this->city,
        ];

        if ($this->state) {
            array_push($components, $this->state->getId());
        }

        if ($this->postalCode) {
            array_push($components, $this->postalCode->getId());
        }

        if ($this->country) {
            array_push($components, $this->country->getId());
        }

        return md5(implode(",", $components));
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Address
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Address
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Update timestamps before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime());
        }

        $this->updateHash();
    }

    public function __toString()
    {
        $address = '';

        if ($this->line1) {
            $address .= $this->line1;
        }

        if ($this->city) {
            $address .= ', '.$this->city;
        }

        if ($this->state) {
            $address .= ', '.$this->state;
        }

        if ($this->postalCode) {
            $address .= ' '.$this->postalCode;
        }

        return $address;
    }
}
