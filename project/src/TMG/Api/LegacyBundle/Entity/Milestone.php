<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Milestone extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $initial;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $accountNumber;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $summary;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $emailsSent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $surveyYes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $surveyNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $resultDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $averageRating;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $internalRating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $internalCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $distributedCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $positiveCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $reviewCount;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     * @return Milestone
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Milestone
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Milestone
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
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return bool
     */
    public function getInitial()
    {
        return $this->initial;
    }

    /**
     * @param bool $v
     *
     * @return Milestone
     */
    public function setInitial($v)
    {
        $this->initial = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string $v
     *
     * @return Milestone
     */
    public function setAccountNumber($v)
    {
        $this->accountNumber = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $v
     *
     * @return Milestone
     */
    public function setSummary($v)
    {
        $this->summary = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getEmailsSent()
    {
        return $this->emailsSent;
    }

    /**
     * @param int $v
     *
     * @return Milestone
     */
    public function setEmailsSent($v)
    {
        $this->emailsSent = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getSurveyYes()
    {
        return $this->surveyYes;
    }

    /**
     * @param int $v
     * @return Milestone
     */
    public function setSurveyYes($v)
    {
        $this->surveyYes = $v;

        return $this;
    }

    /**
     * @return int mixed
     */
    public function getSurveyNo()
    {
        return $this->surveyNo;
    }

    /**
     * @param int $v
     *
     * @return Milestone
     */
    public function setSurveyNo($v)
    {
        $this->surveyNo = $v;

        return $this;
    }

    /**
     * Set resultDate
     *
     * @param \DateTime $resultDate
     * @return Milestone
     */
    public function setResultDate($resultDate)
    {
        $this->resultDate = $resultDate;

        return $this;
    }

    /**
     * Get resultDate
     *
     * @return \DateTime
     */
    public function getResultDate()
    {
        return $this->resultDate;
    }

    /**
     * @return int
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * @param string $v
     *
     * @return Milestone
     */
    public function setAverageRating($v)
    {
        $this->averageRating = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getInternalRating()
    {
        return $this->internalRating;
    }

    /**
     * @param string $v
     *
     * @return Milestone
     */
    public function setInternalRating($v)
    {
        $this->internalRating = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getInternalCount()
    {
        return $this->internalCount;
    }

    /**
     * @param int $v
     *
     * @return Milestone
     */
    public function setInternalCount($v)
    {
        $this->internalCount = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getDistributedCount()
    {
        return $this->distributedCount;
    }

    /**
     * @param int $v
     * @return Milestone
     */
    public function setDistributedCount($v)
    {
        $this->distributedCount = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getPositiveCount()
    {
        return $this->positiveCount;
    }

    /**
     * @param int $v
     *
     * @return Milestone
     */
    public function setPositiveCount($v)
    {
        $this->positiveCount = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getReviewCount()
    {
        return $this->reviewCount;
    }

    /**
     * @param int $v
     *
     * @return Milestone
     */
    public function setReviewCount($v)
    {
        $this->reviewCount = $v;

        return $this;
    }
}
