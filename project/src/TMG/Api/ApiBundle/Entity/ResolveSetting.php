<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TMG\Api\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Resolve Setting
 *
 * @ORM\Table(name="ResolveSetting")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ResolveSettingRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ResolveSetting
{
    const NOT_FOUND_MESSAGE_EMAIL = "Could not find resolve settings assigned an email of %s";

    const NOT_FOUND_MESSAGE_PROPERTY = "Could not find setting for property hash %s";

    public static $fillable = [
        "sla_normal" => false,
        "sla_critical" => false,
        "pre_approved" => false,
        "hotel_notes" => false,
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Property
     **
     * @ORM\OneToOne(targetEntity="Property", inversedBy="resolveSetting", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    protected $property;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="analystUser_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    protected $analyst = null;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="hotelierUser_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    protected $hotelier = null;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResolveSettingSite", mappedBy="resolveSetting", cascade={"persist"})
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    protected $resolveSettingSites;

    /**
     * @var integer
     *
     * @ORM\Column(name="sla_normal", type="integer")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    protected $slaNormal;

    /**
     * @var integer
     *
     * @ORM\Column(name="sla_critical", type="integer")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    protected $slaCritical;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="pre_approved_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $preApprovedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose
     */
    protected $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_notes", type="text", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     *     "resolve_property",
     * })
     */
    protected $hotelNotes;

    /**
     * ResolveSetting constructor.
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
     * Set property
     *
     * @param Property $property
     * @return ResolveSetting
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;
        $property->setResolveSetting($this);

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
     * Set analyst user
     *
     * @param User $analyst
     * @return ResolveSetting
     */
    public function setAnalyst(User $analyst)
    {
        $this->analyst = $analyst;

        return $this;
    }

    /**
     * Get analyst user
     *
     * @return User
     */
    public function getAnalyst()
    {
        return $this->analyst;
    }

    /**
     * Remove analyst user
     *
     * @return ResolveSetting
     */
    public function deleteAnalyst()
    {
        $this->analyst = null;

        return $this;
    }

    /**
     * Set hotelier
     *
     * @param User $hotelier
     * @return ResolveSetting
     */
    public function setHotelier(User $hotelier)
    {
        $this->hotelier = $hotelier;

        return $this;
    }

    /**
     * Get hotelier
     *
     * @return User
     */
    public function getHotelier()
    {
        return $this->hotelier;
    }

    /**
     * Remove hotelier user
     *
     * @return ResolveSetting
     */
    public function deleteHotelier()
    {
        $this->hotelier = null;

        return $this;
    }

    /**
     * Set normal sla
     *
     * @param integer $slaNormal
     * @return ResolveSetting
     */
    public function setSlaNormal($slaNormal)
    {
        if ($slaNormal > 0) {
            $this->slaNormal = $slaNormal;
        } else {
            $this->slaNormal = 0;
        }

        return $this;
    }

    /**
     * Get normal sla
     *
     * @return integer
     */
    public function getSlaNormal()
    {
        return $this->slaNormal;
    }

    /**
     * Set critical sla
     *
     * @param integer $slaCritical
     * @return ResolveSetting
     */
    public function setSlaCritical($slaCritical)
    {
        if ($slaCritical > 0) {
            $this->slaCritical = $slaCritical;
        } else {
            $this->slaCritical = 0;
        }

        return $this;
    }

    /**
     * Get critical sla
     *
     * @return integer
     */
    public function getSlaCritical()
    {
        return $this->slaCritical;
    }

    /**
     * Set PreApprovedAt
     *
     * @param DateTime $preApprovedAt
     * @return ResolveSetting
     */
    public function setPreApprovedAt($preApprovedAt)
    {
        $this->preApprovedAt = $preApprovedAt;

        return $this;
    }

    /**
     * Get PreApprovedAt
     *
     * @return DateTime
     */
    public function getPreApprovedAt()
    {
        return $this->preApprovedAt;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     * @return ResolveSetting
     */
    public function setCreatedAt(DateTime $createdAt)
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
     * @return ResolveSetting
     */
    public function setUpdatedAt(DateTime $updatedAt)
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
     * Set hotelNotes
     *
     * @param string $hotelNotes
     *
     * @return ResolveSetting
     */
    public function setHotelNotes($hotelNotes)
    {
        $this->hotelNotes = $hotelNotes;

        return $this;
    }

    /**
     * Get hotelNotes
     *
     * @return string
     */
    public function getHotelNotes()
    {
        return $this->hotelNotes;
    }

    /**
     * Add resolveSettingSite
     *
     * @param ResolveSettingSite $resolveSettingSite
     *
     * @return ResolveSetting
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
}
