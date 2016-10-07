<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ReputationSite
 *
 * @ORM\Table(name="ReputationSites")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationSiteRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ReputationSite
{
    const NOT_FOUND_MESSAGE = "Could not find reputation site with an id of %s";

    public static $resolveSites = [
        18, //TripAdvisor
        19, //Booking
        20, //Google+
        21, //Yelp
        22, //Expedia
        23, //Orbitz
        24, //Hotels
        33, //Facebook
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
     * @ORM\Column(name="hash", type="string", length=8)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     *     "resolve_property",
     * })
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResolveSettingSite", mappedBy="reputationSite", cascade={"persist"})
     */
    protected $resolveSettingSites;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * ReputationSite constructor.
     */
    public function __construct()
    {
        $this->resolveSettingSites = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return ReputationSite
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
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ReputationSite
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return ReputationSite
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
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
        $this->setUpdatedAt(new DateTime());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime());
        }
    }

    /**
     * Add resolveSettingSite
     *
     * @param ResolveSettingSite $resolveSettingSite
     *
     * @return ReputationSite
     */
    public function addResolveSettingSite(ResolveSettingSite $resolveSettingSite)
    {
        $this->resolveSettingSites[] = $resolveSettingSite;

        return $this;
    }

    /**
     * Remove resolveSettingSite
     *
     * @param ResolveSettingSite $resolveSettingSite
     */
    public function removeResolveSettingSite(ResolveSettingSite $resolveSettingSite)
    {
        $this->resolveSettingSites->removeElement($resolveSettingSite);
    }

    /**
     * Get resolveSettingSites
     *
     * @return Collection
     */
    public function getResolveSettingSites()
    {
        return $this->resolveSettingSites;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return ReputationSite
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
     * Update timestamps before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function checkHash()
    {
        if ($this->getHash() == null) {
            $this->setHash(hash("crc32b", $this->getName()));
        }
    }
}
