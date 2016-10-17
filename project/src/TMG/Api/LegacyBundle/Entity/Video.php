<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="videos")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Video extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose
     */
    protected $id;

    /**
     * @var Property
     *
     * @ORM\OneToOne(targetEntity="Property", mappedBy="video", cascade={"persist"})
     */
    protected $property;

    /**
     * @var CombinedListing
     *
     * @ORM\OneToOne(targetEntity="CombinedListing", mappedBy="video", cascade={"persist"})
     */
    protected $listing;

    /**
     * @var int
     *
     * 1 - Newly purchased
     * 2 - In production
     * 3 - Ready for review
     * 4 - Live
     * 5 - Disabled / Turned off
     *
     * @ORM\Column(type="smallint")
     *
     * @Serializer\Expose
     */
    protected $status;

    /**
     * @ORM\Column(type="text")
     *
     * @Serializer\Expose
     */
    protected $transcript;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $notes;

    /**
     * Length of the video in seconds
     *
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose
     */
    protected $duration;

    /**
     * @orm\column(type="string", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $url;

    /**
     * @orm\column(type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $playerId;

    /**
     * @orm\column(type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $vidyardId;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $vidyardInline;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $vidyardIframe;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $vidyardLightBox;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $submittedDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $submittedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishedDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $publishedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $noteUpdated;

    /**
     * The path within the S3 bucket at which the video file is located.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $hostingPath;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return Video
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;
        $v->setVideo($this);

        return $this;
    }

    /**
     * @return CombinedListing
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @param CombinedListing $v
     *
     * @return Video
     */
    public function setListing(CombinedListing $v)
    {
        $this->listing = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $v
     *
     * @return Video
     */
    public function setStatus($v)
    {
        $this->status = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getTranscript()
    {
        return $this->transcript;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setTranscript($v)
    {
        $this->transcript = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setTitle($v)
    {
        $this->title = $v;

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
     * @return Video
     */
    public function setDescription($v)
    {
        $this->description = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setNotes($v)
    {
        $this->notes = $v;

        return $this;
    }


    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $v
     *
     * @return Video
     */
    public function setDuration($v)
    {
        $this->duration = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setUrl($v)
    {
        $this->url = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @param int $v
     *
     * @return Video
     */
    public function setPlayerId($v)
    {
        $this->playerId = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getVidyardId()
    {
        return $this->vidyardId;
    }

    /**
     * @param int $v
     *
     * @return Video
     */
    public function setVidyardId($v)
    {
        $this->vidyardId = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getVidyardInline()
    {
        return $this->vidyardInline;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setVidyardInline($v)
    {
        $this->vidyardInline = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getVidyardIframe()
    {
        return $this->vidyardIframe;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setVidyardIframe($v)
    {
        $this->vidyardIframe = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getVidyardLightBox()
    {
        return $this->vidyardLightBox;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setVidyardLightBox($v)
    {
        $this->vidyardLightBox = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSubmittedDate()
    {
        return $this->submittedDate;
    }

    /**
     * @param \DateTime
     *
     * @return Video
     */
    public function setSubmittedDate($v)
    {
        $this->submittedDate = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubmittedBy()
    {
        return $this->submittedBy;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setSubmittedBy($v)
    {
        $this->submittedBy = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * @param \DateTime
     *
     * @return Video
     */
    public function setPublishedDate($v)
    {
        $this->publishedDate = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublishedBy()
    {
        return $this->publishedBy;
    }

    /**
     * @param string $v
     *
     * @return Video
     */
    public function setPublishedBy($v)
    {
        $this->publishedBy = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getNoteUpdated()
    {
        return $this->noteUpdated;
    }

    /**
     * @param \DateTime $v
     *
     * @return Video
     */
    public function setNoteUpdated($v)
    {
        $this->noteUpdated = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getHostingPath()
    {
        return $this->hostingPath;
    }

    /**
     * @param string $hostingPath
     *
     * @return Video
     */
    public function setHostingPath($hostingPath)
    {
        $this->hostingPath = $hostingPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextStatus()
    {
        return array(
            '1' => 'Newly Purchased',
            '2' => 'In Production',
            '3' => 'Ready for Review',
            '4' => 'Live',
            '5' => 'Disabled / Turned off',
        )[$this->status];
    }
}
