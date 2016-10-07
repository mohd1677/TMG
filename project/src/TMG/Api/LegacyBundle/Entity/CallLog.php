<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**2
 * Call information as provided by Callbutton
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\CallLogRepository")
 */
class CallLog extends AbstractEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * The call ID provided by callbutton
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $callId;
    /**
     * When the call started
     *
     * @ORM\Column(type="datetime")
     */
    protected $startTime;
    /**
     * Call length in seconds
     *
     * @ORM\Column(type="integer")
     */
    protected $duration;
    /**
     * Talk time in seconds (doesn't include time spent ringing)

     * @ORM\Column(type="integer")
     */
    protected $talkTime;
    /**
     * Phone number calling in.
     *
     * @ORM\Column(type="string")
     */
    protected $callerNum;
    /**
     * Phone number caller dialed
     *
     * @ORM\Column(type="string")
     */
    protected $trackingNum;
    /**
     * Number that the caller was directed to.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $endpointNum;
    /**
     * Caller ID style name, or "Cell Phone"
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;
    /**
     * State of the caller
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $state;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $zip;
    /**
     * @ORM\ManyToOne(targetEntity="Property")
     */
    protected $property;
    /**
     * Note attached to the call. Usually the Property#account_number
     *
     * @ORM\Column(type="string")
     */
    protected $propertyNote;
    /**
     * @ORM\Column(type="string")
     */
    protected $campaign;
    /**
     * URL of the call recording, if it exists.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $recordingUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

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
     * @return CallLog
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCallId()
    {
        return $this->callId;
    }

    /**
     * @param string $v
     *
     * @return CallLog
     */
    public function setCallId($v)
    {
        $this->callId = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param \DateTime $v
     *
     * @return CallLog
     */
    public function setStartTime(\DateTime $v)
    {
        $this->startTime = $v;

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
     * @return CallLog
     */
    public function setDuration($v)
    {
        $this->duration = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getTalkTime()
    {
        return $this->talkTime;
    }

    /**
     * @param int $v
     *
     * @return CallLog
     */
    public function setTalkTime($v)
    {
        $this->talkTime = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCallerNum()
    {
        return $this->callerNum;
    }

    /**
     * @param string $v
     *
     * @return CallLog
     */
    public function setCallerNum($v)
    {
        $this->callerNum = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrackingNum()
    {
        return $this->trackingNum;
    }

    /**
     * @param string $v
     *
     * @return CallLog
     */
    public function setTrackingNum($v)
    {
        $this->trackingNum = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpointNum()
    {
        return $this->endpointNum;
    }

    /**
     * @param string $v
     *
     * @return CallLog
     */
    public function setEndpointNum($v)
    {
        $this->endpointNum = empty($v) ? null : $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $v
     *
     * @return CallLog
     */
    public function setName($v)
    {
        $this->name = empty($v) ? null : $v;

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
     * @return CallLog
     */
    public function setState($v)
    {
        $this->state = empty($v) ? null : $v;

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
     * @return CallLog
     */
    public function setZip($v)
    {
        $this->zip = empty($v) ? null : $v;

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
     * @return CallLog
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyNote()
    {
        return $this->propertyNote;
    }

    /**
     * @param string $v
     *
     * @return CallLog
     */
    public function setPropertyNote($v)
    {
        $this->propertyNote = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param string $v
     *
     * @return CallLog
     */
    public function setCampaign($v)
    {
        $this->campaign = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecordingUrl()
    {
        return $this->recordingUrl;
    }

    /**
     * @param string $v
     *
     * @return CallLog
     */
    public function setRecordingUrl($v)
    {
        $this->recordingUrl = empty($v) ? null : $v;

        return $this;
    }

    /**
     * Get playback URL of call record since it will be archived after 5 days.
     *
     * @return null|string
     */
    public function getUrl()
    {
        $archivedUrl = null;
        if ($this->talkTime < 1 || $this->recordingUrl == null || empty($this->recordingUrl)) {
            return null;
        }

        //if not old than 5 days
        if ($this->startTime->format('Y-m-d') >= date('Y-m-d', strtotime('-5 days'))) {
            return 'http://' . $this->recordingUrl;
        } else {
            $contents = explode('/', $this->recordingUrl);
            $archivedUrl = 'http://brimstone.blob.core.windows.net/'
                . $this->startTime->format('Y/md')
                . '/'
                . strtolower(end($contents));
        }

        return $archivedUrl;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CallLog
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
     *
     * @return CallLog
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
