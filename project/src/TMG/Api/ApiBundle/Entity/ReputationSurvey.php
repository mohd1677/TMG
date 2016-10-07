<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;

/**
 * ReputationSurvey
 *
 * @ORM\Table(name="ReputationSurveys")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationSurveyRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 *
 */
class ReputationSurvey
{
    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Reputation", inversedBy="surveys")
     * @ORM\JoinColumn(name="reputation_id", referencedColumnName="id")
     **/
    private $reputation;

    /**
     * @ORM\ManyToOne(targetEntity="ReputationCustomer", cascade={"persist"})
     * @ORM\JoinColumn(name="customer", referencedColumnName="id")
     *
     * @Serializer\Expose
     *
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="ReputationSource", cascade={"persist"})
     * @ORM\JoinColumn(name="source", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="email_type", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $emailType;

    /**
     * @var integer
     *
     * @ORM\Column(name="open", type="integer")
     *
     * @Serializer\Expose
     */
    private $open;

    /**
     * @var integer
     *
     * @ORM\Column(name="yes", type="integer")
     *
     * @Serializer\Expose
     */
    private $yes;

    /**
     * @var integer
     *
     * @ORM\Column(name="no", type="integer")
     *
     * @Serializer\Expose
     */
    private $no;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="response_date", type="datetime")
     *
     * @Serializer\Expose
     */
    private $responseDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="yrmo", type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    private $yrmo;

    /**
     * @var integer
     *
     * @ORM\Column(name="overall_rating", type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    private $overallRating;

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
     * @ORM\OneToMany(targetEntity="ReputationQuestion", mappedBy="survey")
     *
     * @Serializer\Expose
     */
    private $questions;

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
     * Set reputation
     *
     * @param string $reputation
     *
     * @return ReputationSurvey
     */
    public function setReputation($reputation)
    {
        $this->reputation = $reputation;

        return $this;
    }

    /**
     * Get reputation
     *
     * @return string
     */
    public function getReputation()
    {
        return $this->reputation;
    }

    /**
     * Set customer
     *
     * @param string $customer
     *
     * @return ReputationSurvey
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return string
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return ReputationSurvey
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set emailType
     *
     * @param string $emailType
     *
     * @return ReputationSurvey
     */
    public function setEmailType($emailType)
    {
        $this->emailType = $emailType;

        return $this;
    }

    /**
     * Get emailType
     *
     * @return string
     */
    public function getEmailType()
    {
        return $this->emailType;
    }

    /**
     * Set open
     *
     * @param integer $open
     *
     * @return ReputationSurvey
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return integer
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set yes
     *
     * @param integer $yes
     *
     * @return ReputationSurvey
     */
    public function setYes($yes)
    {
        $this->yes = $yes;

        return $this;
    }

    /**
     * Get yes
     *
     * @return integer
     */
    public function getYes()
    {
        return $this->yes;
    }

    /**
     * Set no
     *
     * @param integer $no
     *
     * @return ReputationSurvey
     */
    public function setNo($no)
    {
        $this->no = $no;

        return $this;
    }

    /**
     * Get no
     *
     * @return integer
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * Set responseDate
     *
     * @param \DateTime $responseDate
     *
     * @return ReputationSurvey
     */
    public function setResponseDate($responseDate)
    {
        $this->responseDate = $responseDate;

        return $this;
    }

    /**
     * Get responseDate
     *
     * @return \DateTime
     */
    public function getResponseDate()
    {
        return $this->responseDate;
    }

    /**
     * Set yrmo
     *
     * @param integer|DateTime $yrmo
     *
     * @return ReputationSurvey
     */
    public function setYrmo($yrmo)
    {
        $this->yrmo = $this->formatYRMO($yrmo);

        return $this;
    }

    /**
     * Get yrmo
     *
     * @return integer
     */
    public function getYrmo()
    {
        return $this->yrmo;
    }

    /**
     * Get Overall rating
     *
     * @return integer
     */
    public function getOverallRating()
    {
        return $this->overallRating;
    }

    /**
     * Set Overall rating
     *
     * @param integer $overallRating
     *
     * @return ReputationSurvey
     */
    public function setOverallRating($overallRating)
    {
        $this->overallRating = $overallRating;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ReputationSurvey
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
     *
     * @return ReputationSurvey
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
     * Set questions
     *
     * @return ReputationSurvey
     */
    public function setQuestions(ArrayCollection $questions)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * Get questions
     *
     * @return ArrayCollection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add question
     *
     * @param ReputationQuestion $question
     * @return ReputationSurvey
     */
    public function addQuestion(ReputationQuestion $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param ReputationQuestion $question
     * @return ReputationSurvey
     */
    public function removeQuestion(ReputationQuestion $question)
    {
        $this->questions->removeElement($question);

        return $this;
    }

    /**
     * Has question
     *
     * @param ReputationQuestion $question
     * @return boolean
     */
    public function hasQuestion(ReputationQuestion $question)
    {
        return $this->questions->contains($question);
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

    /**
     * Format YRMO
     *
     * @return integer
     */
    public function formatYRMO($date)
    {
        if ($date instanceof \DateTime) {
            $date = $date->format('ym');
            return (int) $date;
        } else {
            $date = new \DateTime($date);
            $date = $date->format('ym');
            return (int) $date;
        }
    }
}
