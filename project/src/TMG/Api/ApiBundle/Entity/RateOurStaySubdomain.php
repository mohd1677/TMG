<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use TMG\Api\ApiBundle\Entity\Property;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="RateOurStaySubdomain")
 *  @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\RateOurStaySubdomainRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class RateOurStaySubdomain
{

    const NOT_FOUND_MESSAGE = "Could not find subdomain %s";

    /**
     * Fields that are required when creating a new Property.
     * @var array
     */
    public static $requiredPostFields = [
        "subdomain" => true
    ];

    /**
     * The record ID
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The subdomain
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose
     */
    protected $subdomain;

    /**
     * Timestamp of record creation
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Expose
     */
    protected $createdAt;

    /**
     * Timestamp of last update
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Expose
     */
    protected $updatedAt;

    /**
     * The RateOurStayData
     *
     * @ORM\ManyToOne(targetEntity="RateOurStayData", inversedBy="subdomain", cascade={"persist"})
     * @ORM\JoinColumn(name="rateOurStayData_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    protected $rateOurStayData;

    /**
     * The TripStayWinData
     *
     * @ORM\ManyToOne(targetEntity="TripStayWinData", inversedBy="subdomain", cascade={"persist"})
     * @ORM\JoinColumn(name="tripStayWinData_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    protected $tripStayWinData;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param string $subdomain
     *
     * @return RateOurStaySubdomain
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * @return RateOurStayData
     */
    public function getRateOurStayData()
    {
        return $this->rateOurStayData;
    }

    /**
     * @param RateOurStayData $rateOurStayData
     *
     * @return RateOurStaySubdomain
     */
    public function setRateOurStayData(RateOurStayData $rateOurStayData)
    {
        $this->rateOurStayData = $rateOurStayData;

        return $this;
    }

    /**
     * @return TripStayWinData
     */
    public function getTripStayWinData()
    {
        return $this->tripStayWinData;
    }

    /**
     * @param TripStayWinData
     *
     * @return RateOurStaySubdomain
     */
    public function setTripStayWinData(TripStayWinData $tripStayWinData)
    {
        $this->tripStayWinData = $tripStayWinData;

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
     * Get updatedAt
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return RateOurStaySubdomain
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Update timestamps before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if ($this->getCreatedAt() == null) {
            $this->createdAt = new \DateTime(date('Y-m-d H:i:s'));
        }
    }
}
