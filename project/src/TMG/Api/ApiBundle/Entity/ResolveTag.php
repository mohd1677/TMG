<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Resolve Tags
 *
 * @ORM\Table(name="ResolveTag")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ResolveTagRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("all")
 */
class ResolveTag
{
    const NOT_FOUND_MESSAGE = "Could not find any resolve tags.";
    const NOT_FOUND_MESSAGE_HASH = "Could not find tag with hash of %s";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="ResolveReviewTag", mappedBy="resolveTag", cascade={"persist"})
     *
     */
    protected $resolveReviewTag;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $description;

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
     * @return ResolveTag
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
     * Set tag
     *
     * @param string $tag
     *
     * @return ResolveTag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return ResolveTag
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
     * Set description
     *
     * @param string $description
     *
     * @return ResolveTag
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
            $date = new DateTime();
            $hash = hash("crc32b", $this->tag.$date->getTimestamp());

            $this->setHash($hash);
        }
    }
}
