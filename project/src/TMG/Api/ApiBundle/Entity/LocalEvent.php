<?php

namespace TMG\Api\ApiBundle\Entity;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use TMG\Api\ApiBundle\Entity\Property;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Local Events
 *
 * @ORM\Table(name="LocalEvent")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\LocalEventRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 *
 * @Serializer\ExclusionPolicy("all")
 */
class LocalEvent
{
    const STATUS_NEW = 'New';
    const STATUS_PENDING = 'Pending';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CANCELLED = 'Cancelled';

    const NOT_FOUND_MESSAGE = "Could not find local event with hash code of %s";

    /**
     * Local Event status menu.
     * @var array
     * @Serializer\Expose
    */
    public static $statusMenu = [
        self::STATUS_NEW,
        self::STATUS_PENDING,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    public static $requiredPostFields = [
        "use_funding" => false,
        "title" => true,
        "url" => false,
        "image_url" => false,
        "body" => true,
        "notes" => false,
        "scheduled_at" => true,
    ];

    public static $requiredPutFields = [
        "status" => false,
        "use_funding" => false,
        "title" => false,
        "url" => false,
        "image_url" => false,
        "body" => false,
        "notes" => false,
        "scheduled_at" => false,
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
     * @var Property
     *
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="localEvents")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $status = self::STATUS_NEW;

    /**
     * @var boolean
     *
     * @ORM\Column(name="use_funding", type="boolean", nullable=true)
     *
     * @Serializer\Expose
     */
    private $useFunding;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     *
     * @Serializer\Expose
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $notes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scheduled_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $scheduledAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     *
     * @Serializer\Expose
     */
    private $hash;


    public function __construct()
    {
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
     * @return LocalEvent
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return LocalEvent
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set useFunding
     *
     * @param boolean $useFunding
     * @return LocalEvent
     */
    public function setUseFunding($useFunding)
    {
        $this->useFunding = $useFunding;

        return $this;
    }

    /**
     * Get useFunding
     *
     * @return boolean
     */
    public function getUseFunding()
    {
        return $this->useFunding;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return LocalEvent
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return LocalEvent
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     * @return LocalEvent
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return LocalEvent
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return LocalEvent
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set ScheduledAt
     *
     * @param \DateTime $ScheduledAt
     * @return LocalEvent
     */
    public function setScheduledAt($scheduledAt)
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    /**
     * Get ScheduledAt
     *
     * @return \DateTime
     */
    public function getScheduledAt()
    {
        return $this->scheduledAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return LocalEvent
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     * @return LocalEvent
     */
    public function setUpdatedAt(\DateTime $updatedAt)
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
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
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

        if ($this->getHash() == null) {
            $hash = hash("crc32b", $this->getCreatedAt()->getTimestamp());
            $this->setHash($hash);
        }
    }
}
