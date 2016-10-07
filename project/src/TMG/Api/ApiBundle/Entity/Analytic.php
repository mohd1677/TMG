<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Analytic
 *
 * @ORM\Table(name="Analytics")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\AnalyticRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Analytic
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
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", nullable=false)
     **/
    private $property;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="report_date", type="datetime")
     */
    private $reportDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="online_rate_clicks", type="integer")
     */
    private $onlineRateClicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="coupon_views", type="integer")
     */
    private $couponViews;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured_ad_clicks", type="integer")
     */
    private $featuredAdClicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="detail_views", type="integer")
     */
    private $detailViews;

    /**
     * @ORM\ManyToOne(targetEntity="DeviceType", cascade={"persist"})
     * @ORM\JoinColumn(name="device", referencedColumnName="id")
     */
    private $device;

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
     * Set property
     *
     * @param Property $property
     * @return Analytic
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
     * Set reportDate
     *
     * @param \DateTime $reportDate
     * @return Analytic
     */
    public function setReportDate($reportDate)
    {
        $this->reportDate = $reportDate;

        return $this;
    }

    /**
     * Get reportDate
     *
     * @return \DateTime
     */
    public function getReportDate()
    {
        return $this->reportDate;
    }

    /**
     * Set onlineRateClicks
     *
     * @param integer $onlineRateClicks
     * @return Analytic
     */
    public function setOnlineRateClicks($onlineRateClicks)
    {
        $this->onlineRateClicks = $onlineRateClicks;

        return $this;
    }

    /**
     * Get onlineRateClicks
     *
     * @return integer
     */
    public function getOnlineRateClicks()
    {
        return $this->onlineRateClicks;
    }

    /**
     * Set couponViews
     *
     * @param integer $couponViews
     * @return Analytic
     */
    public function setCouponViews($couponViews)
    {
        $this->couponViews = $couponViews;

        return $this;
    }

    /**
     * Get couponViews
     *
     * @return integer
     */
    public function getCouponViews()
    {
        return $this->couponViews;
    }

    /**
     * Set featuredAdClicks
     *
     * @param integer $featuredAdClicks
     * @return Analytic
     */
    public function setFeaturedAdClicks($featuredAdClicks)
    {
        $this->featuredAdClicks = $featuredAdClicks;

        return $this;
    }

    /**
     * Get featuredAdClicks
     *
     * @return integer
     */
    public function getFeaturedAdClicks()
    {
        return $this->featuredAdClicks;
    }

    /**
     * Set detailViews
     *
     * @param integer $detailViews
     * @return Analytic
     */
    public function setDetailViews($detailViews)
    {
        $this->detailViews = $detailViews;

        return $this;
    }

    /**
     * Get detailViews
     *
     * @return integer
     */
    public function getDetailViews()
    {
        return $this->detailViews;
    }

    /**
     * Set device
     *
     * @param DeviceType $device
     * @return Analytic
     */
    public function setDevice(DeviceType $device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Analytic
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
     * @return Analytic
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
