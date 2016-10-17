<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CityCenter
 *
 * @ORM\Table(name="CityCenters")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\CityCenterRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CityCenter
{

    public function __construct()
    {
        $this->postalCodes = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="PostalCode", mappedBy="cities")
     */
    private $postalCodes;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State", cascade={"persist"})
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     */
    private $state;

    /**
     * @var country
     *
     * @ORM\ManyToOne(targetEntity="Country", cascade={"persist"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="hero_image", type="string", length=255, nullable=true)
     */
    private $heroImage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="place", type="boolean", nullable=true)
     */
    private $place;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set postalCodes
     *
     * @return CityCenter
     */
    public function setPostalCodes(ArrayCollection $postalCodes)
    {
        $this->postalCodes = $postalCodes;

        return $this;
    }

    /**
     * Get postalCodes
     *
     * @return string
     */
    public function getPostalCodes()
    {
        return $this->postalCodes;
    }

    /**
     * Add PostalCode
     *
     * @param PostalCode $postalCode
     * @return CityCenter
     */
    public function addPostalCode(PostalCode $postalCode)
    {
        $this->postalCodes[] = $postalCode;
        return $this;
    }

    /**
     * Remove PostalCode
     *
     * @param PostalCode $postalCode
     * @return CityCenter
     */
    public function removePostalCode(PostalCode $postalCode)
    {
        $this->postalCodes->removeElement($postalCode);
        return $this;
    }

    /**
     * Has PostalCode
     *
     * @param PostalCode $postalCode
     * @return boolean
     */
    public function hasPostalCode(PostalCode $postalCode)
    {
        return $this->postalCodes->contains($postalCode);
    }

    /**
     * Set city
     *
     * @param string $city
     * @return CityCenter
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
     * @return CityCenter
     */
    public function setState(State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return CityCenter
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return CityCenter
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
     * @return CityCenter
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
     * Set heroImage
     *
     * @param string $heroImage
     * @return CityCenter
     */
    public function setHeroImage($heroImage)
    {
        $this->heroImage = $heroImage;

        return $this;
    }

    /**
     * Get heroImage
     *
     * @return string
     */
    public function getHeroImage()
    {
        return $this->heroImage;
    }

    /**
     * Set place
     *
     * @param boolean $place
     * @return CityCenter
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return boolean
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CityCenter
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
     * @return CityCenter
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
    }
}
