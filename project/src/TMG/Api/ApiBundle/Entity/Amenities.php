<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Amenities
 *
 * @ORM\Table(name="Amenities")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\AmenitiesRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Amenities
{
    const NOT_FOUND_MESSAGE = "Could not find amenity with the id of %s";


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="key_selector", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $keySelector;

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
     * Set name
     *
     * @param string $name
     * @return Amenities
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Amenities
     */
    public function setSlug($slug)
    {
        $slug = str_replace('.', '', $slug);
        $slug = preg_replace('/[^a-z0-9\/]/i', '-', strtolower($slug));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = str_replace('/', '_', $slug);
        $slug = trim($slug, '-');
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set keySelector
     *
     * @param string $keySelector
     * @return Amenities
     */
    public function setKeySelector($keySelector)
    {
        $this->keySelector = $keySelector;

        return $this;
    }

    /**
     * Get keySelector
     *
     * @return string
     */
    public function getKeySelector()
    {
        return $this->keySelector;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Amenities
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
     * @return Amenities
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
