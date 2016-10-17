<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReputationCompetitor
 *
 * @ORM\Table(name="ReputationCompetitors")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationCompetitorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ReputationCompetitor
{
    public function __construct()
    {
        $this->competitorData = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Reputation", inversedBy="competitors")
     * @ORM\JoinColumn(name="reputation_id", referencedColumnName="id")
     **/
    private $reputation;

    /**
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="lifetime_rating", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $lifetimeRating;

    /**
     * @var integer
     *
     * @ORM\Column(name="lifetime_reviews", type="integer", nullable=true)
     */
    private $lifetimeReviews;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_rank", type="integer", nullable=true)
     */
    private $cityRank;

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
     * @ORM\OneToMany(targetEntity="ReputationCompetitorData", mappedBy="competitor")
     **/
    private $competitorData;


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
     * @return ReputationCompetitor
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
     * Set address
     *
     * @param Address $address
     *
     * @return ReputationCompetitor
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ReputationCompetitor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return ReputationCompetitor
     */
    public function setPhone($phone)
    {
        $this->phone = $this->formatPhone($phone);

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set lifetimeRating
     *
     * @param string $lifetimeRating
     *
     * @return ReputationCompetitor
     */
    public function setLifetimeRating($lifetimeRating)
    {
        $this->lifetimeRating = $lifetimeRating;

        return $this;
    }

    /**
     * Get lifetimeRating
     *
     * @return string
     */
    public function getLifetimeRating()
    {
        return $this->lifetimeRating;
    }

    /**
     * Set lifetimeReviews
     *
     * @param integer $lifetimeReviews
     *
     * @return ReputationCompetitor
     */
    public function setLifetimeReviews($lifetimeReviews)
    {
        $this->lifetimeReviews = $lifetimeReviews;

        return $this;
    }

    /**
     * Get lifetimeReviews
     *
     * @return integer
     */
    public function getLifetimeReviews()
    {
        return $this->lifetimeReviews;
    }

    /**
     * Set cityRank
     *
     * @param integer $cityRank
     *
     * @return ReputationCompetitor
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ReputationCompetitor
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
     * @return ReputationCompetitor
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Set competitorData
     *
     * @return ReputationCompetitor
     */
    public function setCompetitorData(ArrayCollection $competitorData)
    {
        $this->competitorData = $competitorData;

        return $this;
    }

    /**
     * Get competitorData
     *
     * @return ArrayCollection
     */
    public function getCompetitorData()
    {
        return $this->competitorData;
    }

    /**
     * Add competitorData
     *
     * @param ReputationCompetitorData $competitorData
     * @return ReputationCompetitor
     */
    public function addCompetitorData(ReputationCompetitorData $competitorData)
    {
        $this->competitorData[] = $competitorData;
        return $this;
    }

    /**
     * Remove competitorData
     *
     * @param ReputationCompetitorData $competitorData
     * @return ReputationCompetitor
     */
    public function removeCompetitorData(ReputationCompetitorData $competitorData)
    {
        $this->competitorData->removeElement($competitorData);
        return $this;
    }

    /**
     * Has competitorData
     *
     * @param ReputationCompetitorData $competitorData
     * @return boolean
     */
    public function hasCompetitorData(ReputationCompetitorData $competitorData)
    {
        return $this->competitorData->contains($competitorData);
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

    public function formatPhone($phone)
    {
        $cleanPhone = '';
        $phone = str_replace('-', '', $phone);
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(')', '', $phone);
        $phone = str_replace('x', '', $phone);
        $phone = str_replace('.', '', $phone);


        if (strlen($phone) == 10) {
            $area = substr($phone, 0, 3);
            $first = substr($phone, 3, 3);
            $last = substr($phone, -4);
            $cleanPhone = '('.$area.') '.$first.'-'.$last;
        } elseif (strlen($phone) == 11) {
            $phone = substr($phone, 1);
            $area = substr($phone, 0, 3);
            $first = substr($phone, 3, 3);
            $last = substr($phone, -4);
            $cleanPhone = '('.$area.') '.$first.'-'.$last;
        }

        if ($cleanPhone) {
            return $cleanPhone;
        } else {
            return null;
        }
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
