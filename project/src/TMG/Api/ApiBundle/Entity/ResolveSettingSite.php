<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Resolve Setting Site
 *
 * @ORM\Table(name="ResolveSettingSites")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ResolveSettingSiteRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ResolveSettingSite
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ResolveSetting
     *
     * @ORM\ManyToOne(targetEntity="ResolveSetting", inversedBy="resolveSettingSites", cascade={"persist"})
     * @ORM\JoinColumn(name="resolveSetting_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    protected $resolveSetting = null;

    /**
     * @var ReputationSite
     *
     * @ORM\ManyToOne(targetEntity="ReputationSite", inversedBy="resolveSettingSites", cascade={"persist"})
     * @ORM\JoinColumn(name="reputationSite_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    protected $reputationSite = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="effective_at", type="datetime")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    protected $effectiveAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose
     */
    protected $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     */
    protected $createdAt;

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     * @return ResolveSettingSite
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
     * @return ResolveSettingSite
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
            $this->setCreatedAt($this->getUpdatedAt());
        }

        if ($this->getEffectiveAt() == null) {
            $this->setEffectiveAt($this->getUpdatedAt());
        }
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
     * Set resolveSetting
     *
     * @param ResolveSetting $resolveSetting
     *
     * @return ResolveSettingSite
     */
    public function setResolveSetting(ResolveSetting $resolveSetting = null)
    {
        $this->resolveSetting = $resolveSetting;

        return $this;
    }

    /**
     * Get resolveSetting
     *
     * @return ResolveSetting
     */
    public function getResolveSetting()
    {
        return $this->resolveSetting;
    }

    /**
     * Set reputationSite
     *
     * @param ReputationSite $reputationSite
     *
     * @return ResolveSettingSite
     */
    public function setReputationSite(ReputationSite $reputationSite = null)
    {
        $this->reputationSite = $reputationSite;

        return $this;
    }

    /**
     * Get reputationSite
     *
     * @return ReputationSite
     */
    public function getReputationSite()
    {
        return $this->reputationSite;
    }

    /**
     * Set effectiveAt
     *
     * @param DateTime $effectiveAt
     *
     * @return ResolveSettingSite
     */
    public function setEffectiveAt($effectiveAt)
    {
        $this->effectiveAt = $effectiveAt;

        return $this;
    }

    /**
     * Get effectiveAt
     *
     * @return DateTime
     */
    public function getEffectiveAt()
    {
        return $this->effectiveAt;
    }
}
