<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Resolve Review Tag
 *
 * @ORM\Table(name="ResolveReviewTag")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ResolveReviewTagRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ResolveReviewTag
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
     * @var ReputationReview
     *
     * @ORM\ManyToOne(targetEntity="ReputationReview", inversedBy="resolveReviewTag", cascade={"persist"})
     */
    private $reputationReview;

    /**
     * @var ResolveTag
     *
     * @ORM\ManyToOne(targetEntity="ResolveTag", inversedBy="resolveReviewTag", cascade={"persist"})
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $resolveTag;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="smallint")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $value;

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
     * Set reputationReview
     *
     * @param ReputationReview $reputationReview
     * @return ResolveReviewTag
     */
    public function setReputationReview(ReputationReview $reputationReview)
    {
        $this->reputationReview = $reputationReview;

        return $this;
    }

    /**
     * Get reputationReview
     *
     * @return ReputationReview
     */
    public function getReputationReview()
    {
        return $this->reputationReview;
    }

    /**
     * Set resolveTag
     *
     * @param ResolveTag $resolveTag
     * @return ResolveReviewTag
     */
    public function setResolveTag(ResolveTag $resolveTag)
    {
        $this->resolveTag = $resolveTag;

        return $this;
    }

    /**
     * Get resolveTag
     *
     * @return ResolveTag
     */
    public function getResolveTag()
    {
        return $this->resolveTag;
    }

    /**
     * Set value
     *
     * @param $value
     * @return ResolveReviewTag
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }
}
