<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TMG\Api\UserBundle\Entity\User;

/**
 * Rate
 *
 * @ORM\Table(name="Rates")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\RateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Rate
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
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="rates")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     **/
    private $property;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="restrictions", type="string", length=255, nullable=true)
     */
    private $restrictions;

    /**
     * @var type
     *
     * @ORM\ManyToOne(targetEntity="RateType", cascade={"persist"})
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="rate_pretty", type="string", length=255)
     */
    private $ratePretty;

    /**
     * @ORM\Column(name="rate_value", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $rateValue;

    /**
     * @var advertisementType
     *
     * @ORM\ManyToOne(targetEntity="ProductTypes", cascade={"persist"})
     * @ORM\JoinColumn(name="advertisement_type", referencedColumnName="id")
     */
    private $advertisementType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean", nullable=true)
     */
    private $approved;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     **/
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id", nullable=true)
     **/
    private $updatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="show_limit", type="integer", nullable=true)
     */
    private $showLimit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prioritize", type="boolean", nullable=true)
     */
    private $prioritize;


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
     * @return Rate
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Rate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Rate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set restrictions
     *
     * @param string $restrictions
     * @return Rate
     */
    public function setRestrictions($restrictions)
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    /**
     * Get restrictions
     *
     * @return string
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * Set type
     *
     * @param RateType $type
     * @return Rate
     */
    public function setType(RateType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set ratePretty
     *
     * @param string $ratePretty
     * @return Rate
     */
    public function setRatePretty($ratePretty)
    {
        $this->ratePretty = $ratePretty;

        return $this;
    }

    /**
     * Get ratePretty
     *
     * @return string
     */
    public function getRatePretty()
    {
        return $this->ratePretty;
    }

    /**
     * Update ratePretty
     *
     * @return Rate
     */
    public function updateRatePretty()
    {
        $this->ratePretty = $this->generateRatePretty();
    }


    /**
     * Set rateValue
     *
     * @param string $rateValue
     * @return Rate
     */
    public function setRateValue($rateValue)
    {
        $this->rateValue = $rateValue;

        return $this;
    }

    /**
     * Get rateValue
     *
     * @return string
     */
    public function getRateValue()
    {
        return $this->rateValue;
    }

    /**
     * Set advertisementType
     *
     * @param ProductTypes $advertisementType
     * @return Rate
     */
    public function setAdvertisementType(ProductTypes $advertisementType)
    {
        $this->advertisementType = $advertisementType;

        return $this;
    }

    /**
     * Get advertisementType
     *
     * @return string
     */
    public function getAdvertisementType()
    {
        return $this->advertisementType;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return Rate
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     * @return Rate
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Rate
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
     * Set updatedBy
     *
     * @param User $updatedBy
     * @return Rate
     */
    public function setUpdatedBy(User $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Rate
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
     * Set showLimit
     *
     * @param integer $showLimit
     * @return Rate
     */
    public function setShowLimit($showLimit)
    {
        $this->showLimit = $showLimit;

        return $this;
    }

    /**
     * Get showLimit
     *
     * @return integer
     */
    public function getShowLimit()
    {
        return $this->showLimit;
    }

    /**
     * Set prioritize
     *
     * @param boolean $prioritize
     * @return Rate
     */
    public function setPrioritize($prioritize)
    {
        $this->prioritize = $prioritize;

        return $this;
    }

    /**
     * Get prioritize
     *
     * @return boolean
     */
    public function getPrioritize()
    {
        return $this->prioritize;
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

        $this->updateRatePretty();
    }

    public function generateRatePretty()
    {
        $rateValue = $this->rateValue;
        $rateType = $this->type->getName();

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
}
