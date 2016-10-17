<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table(name="Photos")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\PhotoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Photo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="photos")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     **/
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="original", type="string", length=255)
     */
    private $original;

    /**
     * @var string
     *
     * @ORM\Column(name="extra_large", type="string", length=255)
     */
    private $extraLarge;

    /**
     * @var string
     *
     * @ORM\Column(name="large", type="string", length=255)
     */
    private $large;

    /**
     * @var string
     *
     * @ORM\Column(name="medium", type="string", length=255)
     */
    private $medium;

    /**
     * @var string
     *
     * @ORM\Column(name="small", type="string", length=255)
     */
    private $small;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="string", length=255)
     */
    private $thumbnail;

    /**
     * @var string
     *
     * @ORM\Column(name="ice_id", type="string", length=255, nullable=true)
     */
    private $iceId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ice_updated", type="datetime", nullable=true)
     */
    private $iceUpdated;

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
     * Set property
     *
     * @param Property $property
     * @return Photo
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set original
     *
     * @param string $original
     * @return Photo
     */
    public function setOriginal($original)
    {
        $this->original = $original;

        return $this;
    }

    /**
     * Get original
     *
     * @return string
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Set extraLarge
     *
     * @param string $extraLarge
     * @return Photo
     */
    public function setExtraLarge($extraLarge)
    {
        $this->extraLarge = $extraLarge;

        return $this;
    }

    /**
     * Get extraLarge
     *
     * @return string
     */
    public function getExtraLarge()
    {
        return $this->extraLarge;
    }

    /**
     * Set large
     *
     * @param string $large
     * @return Photo
     */
    public function setLarge($large)
    {
        $this->large = $large;

        return $this;
    }

    /**
     * Get large
     *
     * @return string
     */
    public function getLarge()
    {
        return $this->large;
    }

    /**
     * Set medium
     *
     * @param string $medium
     * @return Photo
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;

        return $this;
    }

    /**
     * Get medium
     *
     * @return string
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * Set small
     *
     * @param string $small
     * @return Photo
     */
    public function setSmall($small)
    {
        $this->small = $small;

        return $this;
    }

    /**
     * Get small
     *
     * @return string
     */
    public function getSmall()
    {
        return $this->small;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     * @return Photo
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set iceId
     *
     * @param string $iceId
     * @return Photo
     */
    public function setIceId($iceId)
    {
        $this->iceId = $iceId;

        return $this;
    }

    /**
     * Get iceId
     *
     * @return string
     */
    public function getIceId()
    {
        return $this->iceId;
    }

    /**
     * Set iceUpdated
     *
     * @param \DateTime $iceUpdated
     * @return Photo
     */
    public function setIceUpdated($iceUpdated)
    {
        $this->iceUpdated = $iceUpdated;

        return $this;
    }

    /**
     * Get iceUpdated
     *
     * @return \DateTime
     */
    public function getIceUpdated()
    {
        return $this->iceUpdated;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Photo
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
     * @return Photo
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
