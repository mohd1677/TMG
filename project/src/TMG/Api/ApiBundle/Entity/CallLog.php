<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CallLog
 *
 * @ORM\Table(name="CallLogs")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\CallLogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CallLog
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
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     **/
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="call_id", type="string", length=20)
     */
    private $callId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime")
     */
    private $startTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="talk_time", type="integer")
     */
    private $talkTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="call_num", type="integer")
     */
    private $callNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="tracking_num", type="integer")
     */
    private $trackingNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="endpoint_num", type="integer", nullable=true)
     */
    private $endpointNum;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State", cascade={"persist"})
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=true)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="account", type="integer")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign", type="string", length=255)
     */
    private $campaign;

    /**
     * @var string
     *
     * @ORM\Column(name="recording_url", type="string", length=255, nullable=true)
     */
    private $recordingUrl;

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
     * @var postalCode
     *
     * @ORM\ManyToOne(targetEntity="PostalCode", cascade={"persist"})
     * @ORM\JoinColumn(name="postal_id", referencedColumnName="id", nullable=true)
     */
    private $postalCode;

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
     * @return CallLog
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
     * Set callId
     *
     * @param string $callId
     * @return CallLog
     */
    public function setCallId($callId)
    {
        $this->callId = $callId;

        return $this;
    }

    /**
     * Get callId
     *
     * @return string
     */
    public function getCallId()
    {
        return $this->callId;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return CallLog
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return CallLog
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
     * Set talkTime
     *
     * @param integer $talkTime
     * @return CallLog
     */
    public function setTalkTime($talkTime)
    {
        $this->talkTime = $talkTime;

        return $this;
    }

    /**
     * Get talkTime
     *
     * @return integer
     */
    public function getTalkTime()
    {
        return $this->talkTime;
    }

    /**
     * Set callNum
     *
     * @param integer $callNum
     * @return CallLog
     */
    public function setCallNum($callNum)
    {
        $this->callNum = $callNum;

        return $this;
    }

    /**
     * Get callNum
     *
     * @return integer
     */
    public function getCallNum()
    {
        return $this->callNum;
    }

    /**
     * Set trackingNum
     *
     * @param integer $trackingNum
     * @return CallLog
     */
    public function setTrackingNum($trackingNum)
    {
        $this->trackingNum = $trackingNum;

        return $this;
    }

    /**
     * Get trackingNum
     *
     * @return integer
     */
    public function getTrackingNum()
    {
        return $this->trackingNum;
    }

    /**
     * Set endpointNum
     *
     * @param integer $endpointNum
     * @return CallLog
     */
    public function setEndpointNum($endpointNum)
    {
        $this->endpointNum = $endpointNum;

        return $this;
    }

    /**
     * Get endpointNum
     *
     * @return integer
     */
    public function getEndpointNum()
    {
        return $this->endpointNum;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return CallLog
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set state
     *
     * @param State $state
     * @return CallLog
     */
    public function setState(State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set account
     *
     * @param integer $account
     * @return CallLog
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return integer
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set campaign
     *
     * @param string $campaign
     * @return CallLog
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return string
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set recordingUrl
     *
     * @param string $recordingUrl
     * @return CallLog
     */
    public function setRecordingUrl($recordingUrl)
    {
        $this->recordingUrl = $recordingUrl;

        return $this;
    }

    /**
     * Get recordingUrl
     *
     * @return string
     */
    public function getRecordingUrl()
    {
        return $this->recordingUrl;
    }


    /**
     * Set postalCode
     *
     * @param PostalCode $postalCode
     * @return CallLog
     */
    public function setPostalCode(PostalCode $postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CallLog
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
     * @return CallLog
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
