<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;
use TMG\Api\UserBundle\Entity\User;

/**
 * Resolve Response Rating
 *
 * @ORM\Table(name="ResolveResponseRating")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ResolveResponseRatingRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ResolveResponseRating
{
    public static $fillable = [
        "rating" => false,
        "feedback" => false,
    ];

    public static $rateByLevel = [
        // rate level => dollar value
        1 => '2.00',
        2 => '3.00',
        3 => '4.00',
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ResolveResponse
     **
     * @ORM\OneToOne(targetEntity="ResolveResponse", inversedBy="resolveResponseRating", cascade={"persist"})
     * @ORM\JoinColumn(name="resolve_response", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $resolveResponse;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\UserBundle\Entity\User",
     *     inversedBy="resolveResponseRatingRatedBy", cascade={"persist"})
     * @ORM\JoinColumn(name="ratedBy", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $ratedBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\UserBundle\Entity\User",
     *     inversedBy="resolveResponseRatingProposedBy", cascade={"persist"})
     * @ORM\JoinColumn(name="proposedBy", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $proposedBy;

    /**
     * @var ResolveResponseRating
     *
     * @ORM\ManyToOne(targetEntity="ResolveContractorInvoice", inversedBy="resolveResponseRating", cascade={"persist"})
     * @ORM\JoinColumn(name="resolveContractorInvoice", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $resolveContractorInvoice;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $rating;

    /**
     * @var float
     *
     * @ORM\Column(name="payment_value", type="decimal", precision=5, scale=2)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $paymentValue;

    /**
     * @var string
     *
     * @ORM\Column(name="feedback", type="text", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $feedback;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $createdAt;

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
     * Set rating
     *
     * @param integer $rating
     *
     * @return ResolveResponseRating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set paymentValue
     *
     * @param string $paymentValue
     *
     * @return ResolveResponseRating
     */
    public function setPaymentValue($paymentValue)
    {
        $this->paymentValue = $paymentValue;

        return $this;
    }

    /**
     * Get paymentValue
     *
     * @return float
     */
    public function getPaymentValue()
    {
        return $this->paymentValue;
    }

    /**
     * Set feedback
     *
     * @param string $feedback
     *
     * @return ResolveResponseRating
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get feedback
     *
     * @return string
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ResolveResponseRating
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ResolveResponseRating
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
     * Set resolveResponse
     *
     * @param ResolveResponse $resolveResponse
     *
     * @return ResolveResponseRating
     */
    public function setResolveResponse(ResolveResponse $resolveResponse = null)
    {
        $this->resolveResponse = $resolveResponse;

        return $this;
    }

    /**
     * Get resolveResponse
     *
     * @return ResolveResponse
     */
    public function getResolveResponse()
    {
        return $this->resolveResponse;
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
     * Set ratedBy
     *
     * @param User $ratedBy
     *
     * @return ResolveResponseRating
     */
    public function setRatedBy(User $ratedBy = null)
    {
        $this->ratedBy = $ratedBy;

        return $this;
    }

    /**
     * Get ratedBy
     *
     * @return User
     */
    public function getRatedBy()
    {
        return $this->ratedBy;
    }

    /**
     * Set proposedBy
     *
     * @param User $proposedBy
     *
     * @return ResolveResponseRating
     */
    public function setProposedBy(User $proposedBy = null)
    {
        $this->proposedBy = $proposedBy;

        return $this;
    }

    /**
     * Get proposedBy
     *
     * @return User
     */
    public function getProposedBy()
    {
        return $this->proposedBy;
    }

    /**
     * Set resolveContractorInvoice
     *
     * @param ResolveContractorInvoice $resolveContractorInvoice
     *
     * @return ResolveResponseRating
     */
    public function setResolveContractorInvoice(ResolveContractorInvoice $resolveContractorInvoice = null)
    {
        $this->resolveContractorInvoice = $resolveContractorInvoice;

        return $this;
    }

    /**
     * Get resolveContractorInvoice
     *
     * @return ResolveContractorInvoice
     */
    public function getResolveContractorInvoice()
    {
        return $this->resolveContractorInvoice;
    }
}
