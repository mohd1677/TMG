<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TMG\Api\UserBundle\Entity\User;

use JMS\Serializer\Annotation as Serializer;

/**
 * Video
 *
 * @ORM\Table(name="Videos")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\VideoRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Video
{
    /**
     * Fields that are required when creating a new Property.
     * @var array
     */
    public static $requiredPostFields = [
        "title" => true,
        "summary" => false,
        "note" => false,
        "duration" => false,
        "createUrl" => true,
        "playerId" => false,
        "vidyardId" => false,
        "inLine" => false,
        "iFrame" => false,
        "lightBox" => false,
        "submitted" => false,
        "noteUpdated" => false,
        "active" => false,
    ];

    /** Not found message */
    const NOT_FOUND_MESSAGE = "Could not find video with id of %s";

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
     * @var VideoStatus
     *
     * @ORM\ManyToOne(targetEntity="VideoStatus", cascade={"persist"})
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="Description", inversedBy="video", cascade={"persist"})
     * @ORM\JoinColumn(name="description_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     **/
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="video")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     **/
    private $property;

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
     * @ORM\Column(name="summary", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="create_url", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $createUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="player_id", type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    private $playerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="vidyard_id", type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    private $vidyardId;

    /**
     * @var string
     *
     * @ORM\Column(name="inline", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $inline;

    /**
     * @var string
     *
     * @ORM\Column(name="iframe", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $iframe;

    /**
     * @var string
     *
     * @ORM\Column(name="light_box", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $lightBox;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="submitted", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $submitted;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="submitted_by", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Expose
     **/
    private $submittedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $published;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="published_by", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Expose
     **/
    private $publishedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="note_updated", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $noteUpdated;

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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     *
     * @Serializer\Expose
     */
    private $active;

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
     * Set status
     *
     * @param VideoStatus $status
     * @return Video
     */
    public function setStatus(VideoStatus $status)
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
     * Set description
     *
     * @param Description $description
     * @return Video
     */
    public function setDescription(Description $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set property
     *
     * @param Property $property
     * @return Video
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }


    /**
     * Set title
     *
     * @param string $title
     * @return Video
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
     * Set summary
     *
     * @param string $summary
     * @return Video
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Video
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }


    /**
     * Set duration
     *
     * @param integer $duration
     * @return Video
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set createUrl
     *
     * @param string $createUrl
     * @return Video
     */
    public function setCreateUrl($createUrl)
    {
        $this->createUrl = $createUrl;

        return $this;
    }

    /**
     * Get createUrl
     *
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->createUrl;
    }

    /**
     * Set playerId
     *
     * @param integer $playerId
     * @return Video
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;

        return $this;
    }

    /**
     * Get playerId
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * Set vidyardId
     *
     * @param integer $vidyardId
     * @return Video
     */
    public function setVidyardId($vidyardId)
    {
        $this->vidyardId = $vidyardId;

        return $this;
    }

    /**
     * Get vidyardId
     *
     * @return integer
     */
    public function getVidyardId()
    {
        return $this->vidyardId;
    }

    /**
     * Set inline
     *
     * @param string $inline
     * @return Video
     */
    public function setInline($inline)
    {
        $this->inline = $inline;

        return $this;
    }

    /**
     * Get inline
     *
     * @return string
     */
    public function getInline()
    {
        return $this->inline;
    }

    /**
     * Set iframe
     *
     * @param string $iframe
     * @return Video
     */
    public function setIframe($iframe)
    {
        $this->iframe = $iframe;

        return $this;
    }

    /**
     * Get iframe
     *
     * @return string
     */
    public function getIframe()
    {
        return $this->iframe;
    }

    /**
     * Set lightBox
     *
     * @param string $lightBox
     * @return Video
     */
    public function setLightBox($lightBox)
    {
        $this->lightBox = $lightBox;

        return $this;
    }

    /**
     * Get lightBox
     *
     * @return string
     */
    public function getLightBox()
    {
        return $this->lightBox;
    }

    /**
     * Set submitted
     *
     * @param \DateTime $submitted
     * @return Video
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    /**
     * Get submitted
     *
     * @return \DateTime
     */
    public function getSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Set submittedBy
     *
     * @param User $submittedBy
     * @return Video
     */
    public function setSubmittedBy(User $submittedBy)
    {
        $this->submittedBy = $submittedBy;

        return $this;
    }

    /**
     * Get submittedBy
     *
     * @return string
     */
    public function getSubmittedBy()
    {
        return $this->submittedBy;
    }

    /**
     * Set published
     *
     * @param \DateTime $published
     * @return Video
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return \DateTime
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set publishedBy
     *
     * @param User $publishedBy
     * @return Video
     */
    public function setPublishedBy(User $publishedBy)
    {
        $this->publishedBy = $publishedBy;

        return $this;
    }

    /**
     * Get publishedBy
     *
     * @return string
     */
    public function getPublishedBy()
    {
        return $this->publishedBy;
    }

    /**
     * Set noteUpdated
     *
     * @param \DateTime $noteUpdated
     * @return Video
     */
    public function setNoteUpdated($noteUpdated)
    {
        $this->noteUpdated = $noteUpdated;

        return $this;
    }

    /**
     * Get noteUpdated
     *
     * @return \DateTime
     */
    public function getNoteUpdated()
    {
        return $this->noteUpdated;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Video
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
     * @return Video
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Video
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
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
