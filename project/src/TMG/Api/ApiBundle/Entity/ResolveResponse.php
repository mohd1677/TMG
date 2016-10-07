<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use TMG\Api\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Resolve Responses
 *
 * @ORM\Table(name="ResolveResponse")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ResolveResponseRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("all")
 */
class ResolveResponse
{
    const LAUNCH_DATE = '2016-03-01';

    public static $fillable = [
        "comment" => false,
        "response" => false,
        "role" => true,
        "action" => true,
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
     * @var ReputationReview
     *
     * @ORM\ManyToOne(targetEntity="ReputationReview", inversedBy="resolveResponse", cascade={"persist"})
     */
    private $reputationReview;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\UserBundle\Entity\User", inversedBy="resolveResponse", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var ResolveResponseRating
     *
     * @ORM\OneToOne(targetEntity="ResolveResponseRating", mappedBy="resolveResponse", cascade={"persist"})
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $resolveResponseRating;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="text")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="text")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="text")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $response;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $comment;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return ResolveResponse
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $role
     * @return ResolveResponse
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $action
     * @return ResolveResponse
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set reputationReview
     *
     * @param ReputationReview $reputationReview
     * @return ResolveResponse
     */
    public function setReputationReview(ReputationReview $reputationReview)
    {
        $this->reputationReview = $reputationReview;

        return $this;
    }

    /**
     * Get reputationReview
     *
     * @return reputationReview
     */
    public function getReputationReview()
    {
        return $this->reputationReview;
    }

    /**
     * Set response
     *
     * @param string $response
     * @return ResolveResponse
     */
    public function setResponse($response)
    {
        if ($response) {
            $this->response = $response;
        } else {
            $this->response = "";
        }

        return $this;
    }

    /**
     * Get response
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return ResolveResponse
     */
    public function setComment($comment)
    {
        if ($comment) {
            $this->comment = $comment;
        } else {
            $this->comment = "";
        }

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     * @return ResolveResponse
     */
    public function setCreatedAt(DateTime $createdAt)
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
     * @return ResolveResponse
     */
    public function setUpdatedAt(DateTime $updatedAt)
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
     * Set resolveResponseRating
     *
     * @param ResolveResponseRating $resolveResponseRating
     *
     * @return ResolveResponse
     */
    public function setResolveResponseRating(ResolveResponseRating $resolveResponseRating = null)
    {
        $this->resolveResponseRating = $resolveResponseRating;

        return $this;
    }

    /**
     * Get resolveResponseRating
     *
     * @return ResolveResponseRating
     */
    public function getResolveResponseRating()
    {
        return $this->resolveResponseRating;
    }
}
