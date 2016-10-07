<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use TMG\Api\UserBundle\Entity\User;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ReputationReview
 *
 * @ORM\Table(name="ReputationReviews",
 *     indexes={
 *         @ORM\Index(name="tone", columns={"tone"}),
 *         @ORM\Index(name="critical", columns={"critical"}),
 *         @ORM\Index(name="post_date", columns={"post_date"}),
 *         @ORM\Index(name="created_at", columns={"created_at"}),
 *         @ORM\Index(name="updated_at", columns={"updated_at"}),
 *         @ORM\Index(name="approved_at", columns={"approved_at"}),
 *         @ORM\Index(name="resolved_at", columns={"resolved_at"}),
 *         @ORM\Index(name="responded_at", columns={"responded_at"}),
 *         @ORM\Index(name="tagged_at", columns={"tagged_at"}),
 *         @ORM\Index(name="reserved_at", columns={"reserved_at"}),
 *         @ORM\Index(name="proposed_at", columns={"proposed_at"}),
 *         @ORM\Index(name="resolvable", columns={"resolvable"}),
 *         @ORM\Index(name="proposable", columns={"proposable"}),
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationReviewRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ReputationReview
{
    const NOT_FOUND_MESSAGE_HASH = "Could not find Reputation Review with hash of %s";
    const NOT_FOUND_MESSAGE_ENGAGE_ID = "Could not find Reputation Review with Engage ID of %s";
    const NOT_FOUND_MESSAGE_PROPERTY = "Could not find reputation for property %s";

    /** @var array $genericContent */
    public static $genericContent = [
        'A new Booking review was added',
        'A new Google review was added',
        'A new Expedia review was added',
        'A new Hotels review was added',
        '5 Stars',
        '4 Stars',
        '3 Stars',
        '2 Stars',
        '1 Stars',
        'This review has been hidden because it doesn\'t meet our guidelines.',
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reserved_by", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $reservedBy;

    /**
     * @var Reputation
     *
     * @ORM\ManyToOne(targetEntity="Reputation", inversedBy="reviews")
     * @ORM\JoinColumn(name="reputation_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $reputation;

    /**
     * @var ReputationSite
     *
     * @ORM\ManyToOne(targetEntity="ReputationSite", cascade={"persist"})
     * @ORM\JoinColumn(name="site", referencedColumnName="id")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $site;

    /**
     * @var ResolveResponse
     *
     * @ORM\OneToMany(targetEntity="ResolveResponse", mappedBy="reputationReview", cascade={"persist"})
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    protected $resolveResponse;

    /**
     * @var ResolveReviewTag
     *
     * @ORM\OneToMany(targetEntity="ResolveReviewTag", mappedBy="reputationReview", cascade={"persist"})
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    protected $resolveReviewTag;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true, nullable=true)
     *
     * @Serializer\Expose
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="engage_id", type="string", length=255, unique=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $engageId;

    /**
     * @var integer
     *
     * @ORM\Column(name="yrmo", type="integer", length=4)
     *
     * @Serializer\Expose
     */
    private $yrmo;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="post_date", type="datetime")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $postDate;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="content_short", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $contentShort;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="content_url", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $contentUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="tone", type="decimal", precision=12, scale=2)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $tone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sentiment", type="boolean")
     *
     * @Serializer\Expose
     */
    private $sentiment;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="responded_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $respondedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="approved_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $approvedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="resolved_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $resolvedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="critical", type="boolean", options={"default" = 0})
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $critical = 0;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="reserved_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $reservedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="proposed_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $proposedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="tagged_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $taggedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="resolvable", type="boolean")
     *
     * @Serializer\Expose
     */
    private $resolvable = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="proposable", type="boolean")
     *
     * @Serializer\Expose
     */
    private $proposable = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resolveResponse = new ArrayCollection();
        $this->resolveReviewTag = new ArrayCollection();
    }

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
     * @param string $hash
     *
     * @return ReputationReview
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set reputation
     *
     * @param Reputation $reputation
     *
     * @return ReputationReview
     */
    public function setReputation(Reputation $reputation)
    {
        $this->reputation = $reputation;

        return $this;
    }

    /**
     * Get reputation
     *
     * @return Reputation
     */
    public function getReputation()
    {
        return $this->reputation;
    }

    /**
     * Set site
     *
     * @param ReputationSite $site
     *
     * @return ReputationReview
     */
    public function setSite(ReputationSite $site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return ReputationSite
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set resolveResponse
     *
     * @param ResolveResponse $resolveResponse
     * @return ReputationReview
     */
    public function setResolveResponse(ResolveResponse $resolveResponse)
    {
        $this->resolveResponse[] = $resolveResponse;

        return $this;
    }

    /**
     * Get resolveResponse
     *
     * @return ArrayCollection
     */
    public function getResolveResponse()
    {
        return $this->resolveResponse;
    }

    /**
     * Set engageId
     *
     * @param string $engageId
     *
     * @return ReputationReview
     */
    public function setEngageId($engageId)
    {
        $this->engageId = $engageId;

        return $this;
    }

    /**
     * Get engageId
     *
     * @return string
     */
    public function getEngageId()
    {
        return $this->engageId;
    }

    /**
     * Set yrmo
     *
     * @param integer $yrmo
     *
     * @return ReputationReview
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
     * Set postDate
     *
     * @param DateTime $postDate
     *
     * @return ReputationReview
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;
        $this->yrmo = $this->formatYRMO($postDate);

        return $this;
    }

    /**
     * Get postDate
     *
     * @return DateTime
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return ReputationReview
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set contentShort
     *
     * @param string $contentShort
     *
     * @return ReputationReview
     */
    public function setContentShort($contentShort)
    {
        $this->contentShort = $contentShort;

        return $this;
    }

    /**
     * Get contentShort
     *
     * @return string
     */
    public function getContentShort()
    {
        return $this->contentShort;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return ReputationReview
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set contentUrl
     *
     * @param string $contentUrl
     *
     * @return ReputationReview
     */
    public function setContentUrl($contentUrl)
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }


    /**
     * Get contentUrl
     *
     * @return string
     */
    public function getContentUrl()
    {
        return $this->contentUrl;
    }

    /**
     * Set tone
     *
     * @param string $tone
     *
     * @return ReputationReview
     */
    public function setTone($tone)
    {
        $this->tone = $tone;

        return $this;
    }

    /**
     * Get tone
     *
     * @return string
     */
    public function getTone()
    {
        return $this->tone;
    }

    /**
     * Set sentiment
     *
     * @param boolean $sentiment
     *
     * @return ReputationReview
     */
    public function setSentiment($sentiment)
    {
        $this->sentiment = $sentiment;

        return $this;
    }

    /**
     * Get sentiment
     *
     * @return boolean
     */
    public function getSentiment()
    {
        return $this->sentiment;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ReputationReview
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return ReputationReview
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set respondedAt
     *
     * @param DateTime $respondedAt
     *
     * @return ReputationReview
     */
    public function setRespondedAt($respondedAt)
    {
        $this->respondedAt = $respondedAt;

        return $this;
    }

    /**
     * Get respondedAt
     *
     * @return DateTime|null
     */
    public function getRespondedAt()
    {
        return $this->respondedAt;
    }

    /**
     * Set approvedAt
     *
     * @param DateTime $approvedAt
     *
     * @return ReputationReview
     */
    public function setApprovedAt($approvedAt)
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    /**
     * Get approvedAt
     *
     * @return DateTime|null
     */
    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * Set resolvedAt
     *
     * @param DateTime $resolvedAt
     *
     * @return ReputationReview
     */
    public function setResolvedAt($resolvedAt)
    {
        $this->resolvedAt = $resolvedAt;

        return $this;
    }

    /**
     * Get resolvedAt
     *
     * @return DateTime|null
     */
    public function getResolvedAt()
    {
        return $this->resolvedAt;
    }

    /**
     * Set critical
     *
     * @param boolean $critical
     *
     * @return ReputationReview
     */
    public function setCritical($critical)
    {
        $this->critical = $critical;

        return $this;
    }

    /**
     * Get critical
     *
     * @return boolean
     */
    public function getCritical()
    {
        return $this->critical;
    }

    /**
     * Update timestamps before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new DateTime());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime());
        }
    }

    /**
     * Create Hash
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function createHash()
    {
        if ($this->getHash() == null) {
            $hash = hash("crc32b", $this->engageId);
            $this->setHash($hash);
        }
    }

    /**
     * Format YRMO
     *
     * @param $date
     * @return integer
     */
    public function formatYRMO($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('ym');

            return (int)$date;
        } else {
            $date = new DateTime($date);
            $date = $date->format('ym');

            return (int)$date;
        }
    }

    /**
     * Add resolveResponse
     *
     * @param ResolveResponse $resolveResponse
     *
     * @return ReputationReview
     */
    public function addResolveResponse(ResolveResponse $resolveResponse)
    {
        $this->resolveResponse[] = $resolveResponse;

        return $this;
    }

    /**
     * Remove resolveResponse
     *
     * @param ResolveResponse $resolveResponse
     */
    public function removeResolveResponse(ResolveResponse $resolveResponse)
    {
        $this->resolveResponse->removeElement($resolveResponse);
    }

    /**
     * Get review tags
     *
     * @return ArrayCollection
     */
    public function getResolveReviewTag()
    {
        return $this->resolveReviewTag;
    }

    /**
     * Add resolveReviewTag
     *
     * @param ResolveReviewTag $resolveReviewTag
     *
     * @return ReputationReview
     */
    public function addResolveReviewTag(ResolveReviewTag $resolveReviewTag)
    {
        $this->resolveReviewTag[] = $resolveReviewTag;

        return $this;
    }

    /**
     * Remove resolveReviewTag
     *
     * @param ResolveReviewTag $resolveReviewTag
     */
    public function removeResolveReviewTag(ResolveReviewTag $resolveReviewTag)
    {
        $this->resolveReviewTag->removeElement($resolveReviewTag);
    }

    /**
     * Set reservedAt
     *
     * @param DateTime $reservedAt
     *
     * @return ReputationReview
     */
    public function setReservedAt($reservedAt = null)
    {
        $this->reservedAt = $reservedAt;

        return $this;
    }

    /**
     * Get reservedAt
     *
     * @return DateTime|null
     */
    public function getReservedAt()
    {
        return $this->reservedAt;
    }

    /**
     * Set proposedAt
     *
     * @param DateTime $proposedAt
     *
     * @return ReputationReview
     */
    public function setProposedAt($proposedAt)
    {
        $this->proposedAt = $proposedAt;

        return $this;
    }

    /**
     * Get proposedAt
     *
     * @return DateTime|null
     */
    public function getProposedAt()
    {
        return $this->proposedAt;
    }

    /**
     * Set taggedAt
     *
     * @param DateTime $taggedAt
     *
     * @return ReputationReview
     */
    public function setTaggedAt($taggedAt)
    {
        $this->taggedAt = $taggedAt;

        return $this;
    }

    /**
     * Get taggedAt
     *
     * @return DateTime|null
     */
    public function getTaggedAt()
    {
        return $this->taggedAt;
    }

    /**
     * Set reservedBy
     *
     * @param User $reservedBy
     *
     * @return ReputationReview
     */
    public function setReservedBy(User $reservedBy = null)
    {
        $this->reservedBy = $reservedBy;

        return $this;
    }

    /**
     * Get reservedBy
     *
     * @return User
     */
    public function getReservedBy()
    {
        return $this->reservedBy;
    }

    /**
     * get resolvable
     *
     * @return bool
     */
    public function getResolvable()
    {
        return $this->resolvable;
    }

    /**
     * Set resolvable
     *
     * @param bool $resolvable
     *
     * @return ReputationReview
     */
    public function setResolvable($resolvable)
    {
        $this->resolvable = $resolvable;

        return $this;
    }

    /**
     * get proposable
     *
     * @return bool
     */
    public function getProposable()
    {
        return $this->proposable;
    }

    /**
     * Set proposable
     *
     * @param bool $proposable
     *
     * @return ReputationReview
     */
    public function setProposable($proposable)
    {
        $this->proposable = $proposable;

        return $this;
    }
}
