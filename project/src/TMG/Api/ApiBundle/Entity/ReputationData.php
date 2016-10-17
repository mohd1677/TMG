<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * ReputationData
 *
 * @ORM\Table(name="ReputationData")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationDataRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ReputationData
{
    public function __construct()
    {
        $this->externalStars = [];
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Reputation", inversedBy="reputationData")
     * @ORM\JoinColumn(name="reputation_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     **/
    private $reputation;

    /**
     * @var integer
     *
     * @ORM\Column(name="yrmo", type="integer", length=4)
     *
     * @Serializer\Expose
     */
    private $yrmo;

    /**
     * @var string
     *
     * @ORM\Column(name="external_average_rating", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $externalAverageRating;

    /**
     * @var integer
     *
     * @ORM\Column(name="external_total", type="integer", nullable=true)
     */
    private $externalTotal;

    /**
     * @var array
     *
     * @ORM\Column(name="external_stars", type="array", nullable=true)
     */
    private $externalStars;

    /**
     * @var integer
     *
     * @ORM\Column(name="external_positive", type="integer", nullable=true)
     */
    private $externalPositive;

    /**
     * @var string
     *
     * @ORM\Column(name="trip_advisor_rating", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $tripAdvisorRating;

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
     * @return ReputationData
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
     * Set yrmo
     *
     * @param integer|DateTime $yrmo
     *
     * @return ReputationData
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
     * Set externalAverageRating
     *
     * @param string $externalAverageRating
     *
     * @return ReputationData
     */
    public function setExternalAverageRating($externalAverageRating)
    {
        $this->externalAverageRating = $externalAverageRating;

        return $this;
    }

    /**
     * Get externalAverageRating
     *
     * @return string
     */
    public function getExternalAverageRating()
    {
        return $this->externalAverageRating;
    }

    /**
     * Set externalTotal
     *
     * @param integer $externalTotal
     *
     * @return ReputationData
     */
    public function setExternalTotal($externalTotal)
    {
        $this->externalTotal = $externalTotal;

        return $this;
    }

    /**
     * Get externalTotal
     *
     * @return integer
     */
    public function getExternalTotal()
    {
        return $this->externalTotal;
    }

    /**
     * Set externalStars
     *
     * @param array $externalStars
     *
     * @return ReputationData
     */
    public function setExternalStars($externalStars)
    {
        $this->externalStars = $externalStars;

        return $this;
    }

    /**
     * Get externalStars
     *
     * @return array
     */
    public function getExternalStars()
    {
        return $this->externalStars;
    }

    /**
     * Set externalPositive
     *
     * @param integer $externalPositive
     *
     * @return ReputationData
     */
    public function setExternalPositive($externalPositive)
    {
        $this->externalPositive = $externalPositive;

        return $this;
    }

    /**
     * Get externalPositive
     *
     * @return integer
     */
    public function getExternalPositive()
    {
        return $this->externalPositive;
    }

    /**
     * Set tripAdvisorRating
     *
     * @param string $tripAdvisorRating
     *
     * @return ReputationData
     */
    public function setTripAdvisorRating($tripAdvisorRating)
    {
        $this->tripAdvisorRating = $tripAdvisorRating;

        return $this;
    }

    /**
     * Get tripAdvisorRating
     *
     * @return string
     */
    public function getTripAdvisorRating()
    {
        return $this->tripAdvisorRating;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ReputationData
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
     * @return ReputationData
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
