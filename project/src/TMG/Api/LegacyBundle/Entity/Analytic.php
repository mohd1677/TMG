<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="analytics",
 *     indexes={@ORM\Index(name="report_date_idx", columns={"report_date"})}
 * )
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\AnalyticRepository")
 */
class Analytic extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $reportDate;

    /**
     * @ORM\Column(type="integer")
     */
    protected $onlineRateClicks;

    /**
     * @ORM\Column(type="integer")
     */
    protected $couponViews;

    /**
     * @ORM\Column(type="integer")
     */
    protected $featuredAdClicks;

    /**
     * @ORM\Column(type="integer")
     */
    protected $detailViews;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="analytics")
     *
     * @Assert\Valid
     */
    protected $property;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $deviceType;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $value
     *
     * @return Analytic
     */
    public function setId($value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReportDate()
    {
        return $this->reportDate;
    }

    /**
     * @param \DateTime $value
     *
     * @return Analytic
     */
    public function setReportDate($value)
    {
        $this->reportDate = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getOnlineRateClicks()
    {
        return $this->onlineRateClicks;
    }

    /**
     * @param int $value
     *
     * @return Analytic
     */
    public function setOnlineRateClicks($value)
    {
        $this->onlineRateClicks = $value;

        return $this;
    }

    /**
     * @return int mixed
     */
    public function getCouponViews()
    {
        return $this->couponViews;
    }

    /**
     * @param int $value
     *
     * @return Analytic
     */
    public function setCouponViews($value)
    {
        $this->couponViews = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getFeaturedAdClicks()
    {
        return $this->featuredAdClicks;
    }

    /**
     * @param int $value
     *
     * @return Analytic
     */
    public function setFeaturedAdClicks($value)
    {
        $this->featuredAdClicks = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getDetailViews()
    {
        return $this->detailViews;
    }

    /**
     * @param int $value
     *
     * @return Analytic
     */
    public function setDetailViews($value)
    {
        $this->detailViews = $value;

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
     * @return Analytic
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * @param string $v
     *
     * @return Analytic
     */
    public function setDeviceType($v)
    {
        $this->deviceType = $v;

        return $this;
    }
}
