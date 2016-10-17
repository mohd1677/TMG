<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\PropertyAdvertisementRepository")
 * @ORM\HasLifecycleCallbacks
 */
class PropertyAdvertisement extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="advertisements")
     * @Assert\Valid
     */
    protected $property;

    /**
     * For online advertisements, this url is for the "Online Rate" link
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $url;

    /**
     * DateTime the advertisement takes effect
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $startDate;

    /**
     * DateTime the advertisement expires
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $endDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $restrictions;

    /**
     * The type of coupon this advertisement is for. May be one
     * of the following:
     * <ul>
     *     <li>`dollar` - Coupon is to get a room for a price of {rateValue}</li>
     *     <li>`from-dollar` - Coupon is advertising that rooms start at {rateValue}</li>
     *     <li>`dollar-off` - Coupon provides a fixed dollar amount off of a room price</li>
     *     <li>`percent-off` - Coupon provides a percentage off of a room price</li>
     *     <li>`call-for-rate` - Property must be contacted to recieve a rate</li>
     *     <li>`online-rate` - Visit the propertie's website to view a rate</li>
     * </ul>
     *
     * @ORM\Column(type="string", nullable=false);
     */
    protected $rateType;

    /**
     * How to display the rate to a user
     * @ORM\Column(type="string", nullable=false);
     */
    protected $ratePretty;

    /**
     * The numeric value associated with the rate. Check rateType to know
     * what type of rate this value represents.
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    protected $rateValue;

    /**
     * "print", "online", "special"
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      min = "2",
     *      max = "30",
     *      minMessage = "Advertising Type must be at least {{ limit }} characters.",
     *      maxMessage = "Advertising Type Cannot be longer than {{ limit }} characters."
     * )
     */
    protected $advertisingType;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isApproved;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isLock;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $updatedBy;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $createdBy;

    /**
     * Timestamp of record creation
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Timestamp of last update
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

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
     * @return PropertyAdvertisement
     */
    public function setId($v)
    {
        $this->id = $v;

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
     * @return PropertyAdvertisement
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;

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
     * @return PropertyAdvertisement
     */
    public function setUrl($v)
    {
        if ($v) {
            //make sure that url has leading of "http://"
            if (substr($v, 0, 4) == 'http') {
                $this->url = $v;

                return $this;
            } else {
                $this->url = 'http://' . $v;

                return $this;
            }
        } else {
            $this->url = null;

            return $this;
        }
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $v
     *
     * @return PropertyAdvertisement
     */
    public function setStartDate(DateTime $v)
    {
        $this->startDate = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $v
     *
     * @return PropertyAdvertisement
     */
    public function setEndDate(DateTime $v)
    {
        $this->endDate = $v;

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
     * @return PropertyAdvertisement
     */
    public function setRestrictions($v)
    {
        $this->restrictions = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * @param string $v
     *
     * @return PropertyAdvertisement
     */
    public function setRateType($v)
    {
        $this->rateType = $v;
        $this->updateRatePretty();

        return $this;
    }

    /**
     * @return string
     */
    public function getRatePretty()
    {
        return $this->ratePretty;
    }

    /**
     * @param string $v
     *
     * @return PropertyAdvertisement
     */
    public function setRatePretty($v)
    {
        $this->ratePretty = $v;

        return $this;
    }

    /**
     * @return float
     */
    public function getRateValue()
    {
        return $this->rateValue;
    }

    /**
     * @param string $v
     *
     * @return PropertyAdvertisement
     */
    public function setRateValue($v)
    {
        $this->rateValue = $v;
        $this->updateRatePretty();

        return $this;
    }

    /**
     * @return string
     */
    public function getAdvertisingType()
    {
        return $this->advertisingType;
    }

    /**
     * @param string $v
     *
     * @return PropertyAdvertisement
     */
    public function setAdvertisingType($v)
    {
        $this->advertisingType = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }

    /**
     * @param bool $v
     * @return PropertyAdvertisement
     */
    public function setIsApproved($v)
    {
        $this->isApproved = $v;

        return $this;
    }

    public function updateRatePretty()
    {
        $pretty = self::generateRatePretty($this->rateType, $this->rateValue);

        if ($pretty) {
            $this->ratePretty = $pretty;
        }
    }

    /**
     * @param string $rateType
     * @param float  $rateValue
     *
     * @return null|string
     */
    public static function generateRatePretty($rateType, $rateValue)
    {

        $prettyString = '$0.00';

        setlocale(LC_MONETARY, 'en_US.UTF-8');
        if ($rateValue != null) {
            $prettyString = money_format('%.2n', (double)$rateValue);
        }

        if ($rateType == 'dollar') {
            return $prettyString;
        } elseif ($rateType == 'from-dollar') {
            return 'From '.$prettyString;
        } elseif ($rateType == 'percent-off') {
            return $rateValue.'% Off';
        } elseif ($rateType == 'dollar-off') {
            return $prettyString.' Off';
        } elseif ($rateType == 'call-for-rate') {
            return 'Call for Current Rate';
        } elseif ($rateType == 'online-rate') {
            return 'Click for Online Rate';
        } else {
            return null;
        }
    }


    /**
     * @return bool
     */
    public function getIsLock()
    {
        return $this->isLock;
    }

    /**
     * @param bool $v
     *
     * @return PropertyAdvertisement
     */
    public function setIsLock($v)
    {
        $this->isLock = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param string $v
     *
     * @return PropertyAdvertisement
     */
    public function setUpdatedBy($v)
    {
        $this->updatedBy = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param string $v
     *
     * @return PropertyAdvertisement
     */
    public function setCreatedBy($v)
    {
        $this->createdBy = $v;
        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PropertyAdvertisement
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
     * @return PropertyAdvertisement
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
        $this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
}
