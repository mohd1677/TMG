<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * ReputationCompetitorData
 *
 * @ORM\Table(name="ReputationCompetitorData")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationCompetitorDataRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ReputationCompetitorData
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
     * @ORM\ManyToOne(targetEntity="ReputationCompetitor", inversedBy="competitorData")
     * @ORM\JoinColumn(name="competitor_id", referencedColumnName="id")
     **/
    private $competitor;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="rss_id", type="integer", nullable=true)
     */
    private $rssId;

    /**
     * @var integer
     *
     * @ORM\Column(name="yrmo", type="integer", length=4)
     */
    private $yrmo;

    /**
     * @var string
     *
     * @ORM\Column(name="rating", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $rating;

    /**
     * @var integer
     *
     * @ORM\Column(name="reviews", type="integer", nullable=true)
     */
    private $reviews;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_rank", type="integer", nullable=true)
     */
    private $cityRank;

    /**
     * @ORM\ManyToOne(targetEntity="ReputationSite", cascade={"persist"})
     * @ORM\JoinColumn(name="site", referencedColumnName="id")
     */
    private $site;

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
     * @ORM\Column(name="month_total", type="integer", nullable=true)
     */
    private $monthTotal;


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
     * Set competitor
     *
     * @param ReputationCompetitor $competitor
     *
     * @return ReputationCompetitorData
     */
    public function setCompetitor(ReputationCompetitor $competitor)
    {
        $this->competitor = $competitor;

        return $this;
    }

    /**
     * Get competitor
     *
     * @return ReputationCompetitor
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return ReputationCompetitorData
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set rssId
     *
     * @param integer $rssId
     *
     * @return ReputationCompetitorData
     */
    public function setRssId($rssId)
    {
        $this->rssId = $rssId;

        return $this;
    }

    /**
     * Get rssId
     *
     * @return integer
     */
    public function getRssId()
    {
        return $this->rssId;
    }

    /**
     * Set yrmo
     *
     * @param integer|DateTime $yrmo
     *
     * @return ReputationCompetitorData
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
     * Set rating
     *
     * @param string $rating
     *
     * @return ReputationCompetitorData
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set reviews
     *
     * @param integer $reviews
     *
     * @return ReputationCompetitorData
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * Get reviews
     *
     * @return integer
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set cityRank
     *
     * @param integer $cityRank
     *
     * @return ReputationCompetitorData
     */
    public function setCityRank($cityRank)
    {
        $this->cityRank = $cityRank;

        return $this;
    }

    /**
     * Get cityRank
     *
     * @return integer
     */
    public function getCityRank()
    {
        return $this->cityRank;
    }

    /**
     * Set site
     *
     * @param ReputationSite $site
     *
     * @return ReputationCompetitorData
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ReputationCompetitorData
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
     * @return ReputationCompetitorData
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
     * @return mixed
     */
    public function getMonthTotal()
    {
        return $this->monthTotal;
    }

    /**
     * @param mixed $monthTotal
     */
    public function setMonthTotal($monthTotal)
    {
        $this->monthTotal = $monthTotal;
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
