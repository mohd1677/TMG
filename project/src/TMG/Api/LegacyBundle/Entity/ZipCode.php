<?php

namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a US or CA zip code. May or may not have +4
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\ZipCodeRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"zip"})})
 */
class ZipCode extends AbstractEntity
{
    use LatitudeLongitudeTrait;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * ex: 12345, 12345+6789, A1A 1A1
     *
     * @ORM\Column(type="string")
     */
    protected $zip;

    /**
     * @ORM\Column(type="string")
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $state;

    /**
     * Two letter country code
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $v
     *
     * @return ZipCode
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $v
     *
     * @return ZipCode
     */
    public function setZip($v)
    {
        $this->zip = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $v
     *
     * @return ZipCode
     */
    public function setCity($v)
    {
        $this->city = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $v
     *
     * @return ZipCode
     */
    public function setState($v)
    {
        $this->state = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $v
     *
     * @return ZipCode
     */
    public function setCountry($v)
    {
        $this->country = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return ZipCode
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return ZipCode
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
