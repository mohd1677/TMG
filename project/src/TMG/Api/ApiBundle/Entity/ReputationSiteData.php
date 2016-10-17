<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReputationSiteData
 *
 * @ORM\Table(name="ReputationSiteData")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationSiteDataRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ReputationSiteData
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
     * @ORM\ManyToOne(targetEntity="Reputation", inversedBy="reputationSiteData")
     * @ORM\JoinColumn(name="reputation_id", referencedColumnName="id")
     **/
    private $reputation;

    /**
     * @ORM\ManyToOne(targetEntity="ReputationSite", cascade={"persist"})
     * @ORM\JoinColumn(name="site", referencedColumnName="id")
     */
    private $site;

    /**
     * @var integer
     *
     * @ORM\Column(name="yrmo", type="integer", length=4, nullable=true)
     */
    private $yrmo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lifetime", type="boolean", nullable=true)
     */
    private $lifetime;

    /**
     * @var integer
     *
     * @ORM\Column(name="review_count", type="integer", nullable=true)
     */
    private $reviewCount;

    /**
     * @var string
     *
     * @ORM\Column(name="average_rating", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $averageRating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * @param Reputation $reputation
     *
     * @return ReputationSiteData
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
     * @return ReputationSiteData
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
     * Set yrmo
     *
     * @param integer $yrmo
     *
     * @return ReputationSiteData
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
     * Set lifetime
     *
     * @param boolean $lifetime
     *
     * @return ReputationSiteData
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    /**
     * Get lifetime
     *
     * @return boolean
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * Set reviewCount
     *
     * @param integer $reviewCount
     *
     * @return ReputationSiteData
     */
    public function setReviewCount($reviewCount)
    {
        $this->reviewCount = $reviewCount;

        return $this;
    }

    /**
     * Get reviewCount
     *
     * @return integer
     */
    public function getReviewCount()
    {
        return $this->reviewCount;
    }

    /**
     * Set averageRating
     *
     * @param string $averageRating
     *
     * @return ReputationSiteData
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    /**
     * Get averageRating
     *
     * @return string
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ReputationSiteData
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
     * @return ReputationSiteData
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
