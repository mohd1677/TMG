<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="reputation_reviews",
 *     indexes={@ORM\Index(name="account_number_idx", columns={"account_number"})}
 * )
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\ReviewRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Review extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $submittedAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $representative;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $interactionType;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $source;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $sentiment;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $reviewType;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $account;

    /**
     * @ORM\OneToOne(targetEntity="Customer", inversedBy="review", cascade={"persist"})
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="review", cascade={"persist"})
     */
    protected $questions;

    /**
     * @ORM\OneToMany(targetEntity="Response", mappedBy="review", cascade={"persist"})
     */
    protected $responses;

    /**
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="review", cascade={"persist"})
     */
    protected $ratings;

    /**
     * @ORM\Column(type="integer")
     */
    protected $accountNumber;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $cRating;

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
     * Review constructor.
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->responses = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

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
     * @return Review
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSubmittedAt()
    {
        return $this->submittedAt;
    }

    /**
     * @param \DateTime $v
     *
     * @return Review
     */
    public function setSubmittedAt($v)
    {
        $this->submittedAt = $v;

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
     * @return Review
     */
    public function setUrl($v)
    {
        $this->url = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $v
     *
     * @return Review
     */
    public function setUser($v)
    {
        $this->user = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepresentative()
    {
        return $this->representative;
    }

    /**
     * @param string $v
     *
     * @return Review
     */
    public function setRepresentative($v)
    {
        $this->representative = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getInteractionType()
    {
        return $this->interactionType;
    }

    /**
     * @param string $v
     *
     * @return Review
     */
    public function setInteractionType($v)
    {
        $this->interactionType = $v;

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
     * @return Review
     */
    public function setName($v)
    {
        $this->name = $v;

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
     * @return Review
     */
    public function setDescription($v)
    {
        $this->description = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $v
     *
     * @return Review
     */
    public function setSource($v)
    {
        $this->source = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getSentiment()
    {
        return $this->sentiment;
    }

    /**
     * @param string $v
     *
     * @return Review
     */
    public function setSentiment($v)
    {
        $this->sentiment = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getReviewType()
    {
        return $this->reviewType;
    }

    /**
     * @param string $v
     *
     * @return Review
     */
    public function setReviewType($v)
    {
        $this->reviewType = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param string $v
     *
     * @return Review
     */
    public function setAccount($v)
    {
        $this->account = $v;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $v
     *
     * @return Review
     */
    public function setCustomer(Customer $v)
    {
        $this->customer = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param $v
     *
     * @return Review
     */
    public function setQuestions($v)
    {
        $this->questions[] = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param $v
     *
     * @return Review
     */
    public function setResponses($v)
    {
        $this->responses[] = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param $v
     *
     * @return Review
     */
    public function setRatings($v)
    {
        $this->ratings[] = $v;

        return $this;
    }

    /**
     * @param int $v
     *
     * @return Review
     */
    public function setAccountNumber($v)
    {
        $this->accountNumber = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return float
     */
    public function getCRating()
    {
        return $this->cRating;
    }

    /**
     * @param float $v
     *
     * @return Review
     */
    public function setCRating($v)
    {
        $this->cRating = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Review
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Review
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
        $this->setUpdatedAt(new \DateTime());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}
