<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * PostalCode
 *
 * @ORM\Table(name="PostalCodes")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\PostalCodeRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class PostalCode
{

    const NOT_FOUND_MESSAGE = "Could not find postal code %s";

    public function __construct()
    {
        $this->cities = new ArrayCollection();
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
     * @ORM\ManyToMany(targetEntity="CityCenter", inversedBy="postalCodes")
     * @ORM\JoinTable(name="postal_code_city_centers",
     *      joinColumns={@ORM\JoinColumn(name="postal_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="city_id", referencedColumnName="id")}
     * )
     *
     * @Serializer\Expose
     */
    protected $cities;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10)
     *
     * @Serializer\Expose
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="code_full", type="string", length=55, nullable=true)
     */
    private $codeFull;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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
     * Set cities
     *
     * @return PostalCode
     */
    public function setCities(ArrayCollection $cities)
    {
        $this->cities = $cities;

        return $this;
    }

    /**
     * Get cities
     *
     * @return string
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Add city
     *
     * @param CityCenter $city
     * @return PostalCode
     */
    public function addCity(CityCenter $city)
    {
        $this->cities[] = $city;
        return $this;
    }

    /**
     * Remove city
     *
     * @param CityCenter $city
     * @return PostalCode
     */
    public function removeCity(CityCenter $city)
    {
        $this->cities->removeElement($city);
        return $this;
    }

    /**
     * Has city
     *
     * @param CityCenter $city
     * @return boolean
     */
    public function hasCity(CityCenter $city)
    {
        return $this->cities->contains($city);
    }

    /**
     * Set code
     *
     * @param string $code
     * @return PostalCode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set codeFull
     *
     * @param string $codeFull
     * @return PostalCode
     */
    public function setCodeFull($codeFull)
    {
        $this->codeFull = $codeFull;

        return $this;
    }

    /**
     * Get codeFull
     *
     * @return string
     */
    public function getCodeFull()
    {
        return $this->codeFull;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return PostalCode
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PostalCode
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

    public function __toString()
    {
        return ($this->codeFull) ? $this->codeFull : $this->code;
    }
}
