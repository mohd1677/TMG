<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @Serializer\ExclusionPolicy("all")
 */
class PropertyDescription extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Description used for full page
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected $description;

    /**
     * Description used for blurb
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $briefDescription;

    /**
     * Closest interstate and exit.
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $interstateExit;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $directions;

    /**
     * Used to describe occupancy restrictions, and other room or special
     * restrictions
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $restrictions;

    /**
     * @ORM\OneToOne(targetEntity="Property", inversedBy="description")
     *
     * @Assert\Valid
     */
    protected $property;

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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $displayInterstate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $displayExit;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     *
     * @return PropertyDescription
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $v
     *
     * @return PropertyDescription
     */
    public function setDescription($v)
    {
        $this->description = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getBriefDescription()
    {
        return $this->briefDescription;
    }

    /**
     * @param string $v
     *
     * @return PropertyDescription
     */
    public function setBriefDescription($v)
    {
        $this->briefDescription = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getInterstateExit()
    {
        return $this->interstateExit;
    }

    /**
     * @param string $v
     *
     * @return PropertyDescription
     */
    public function setInterstateExit($v)
    {
        $this->interstateExit = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getDirections()
    {
        return $this->directions;
    }

    /**
     * @param string $v
     *
     * @return PropertyDescription
     */
    public function setDirections($v)
    {
        $this->directions = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * @param string $v
     *
     * @return PropertyDescription
     */
    public function setRestrictions($v)
    {
        $this->restrictions = $v;

        return $this;
    }

    /**
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param Property $v
     *
     * @return PropertyDescription
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PropertyDescription
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return PropertyDescription
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
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Get displayInterstate
     */
    public function getDisplayInterstate()
    {
        return $this->displayInterstate;
    }

    /**
     * Set displayInterstate
     */
    public function setDisplayInterstate($v)
    {
        $this->displayInterstate = $v;
        return $this;
    }

    /**
     * Get displayExit
     */
    public function getDisplayExit()
    {
        return $this->displayExit;
    }

    /**
     * Set displayExit
     */
    public function setDisplayExit($v)
    {
        $this->displayExit = $v;
        return $this;
    }
}
