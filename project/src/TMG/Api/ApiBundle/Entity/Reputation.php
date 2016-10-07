<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Reputation
 *
 * @ORM\Table(name="Reputations")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Reputation
{
    const NOT_FOUND_MESSAGE_PROPERTY = "Could not find reputation for property %s";

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
     * @var Property
     *
     * @ORM\OneToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="reputation")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $property;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     *
     * @Serializer\Expose
     */
    private $active;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
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
     * @var string
     *
     * @ORM\Column(name="external_average_rating", type="decimal", precision=12, scale=2, nullable=true)
     *
     * @Serializer\Expose
     */
    private $externalAverageRating;

    /**
     * @var integer
     *
     * @ORM\Column(name="external_total", type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    private $externalTotal;

    /**
     * @var array
     *
     * @ORM\Column(name="external_stars", type="array", nullable=true)
     *
     * @Serializer\Expose
     */
    private $externalStars;

    /**
     * @var integer
     *
     * @ORM\Column(name="external_positive", type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    private $externalPositive;

    /**
     * @var string
     *
     * @ORM\Column(name="trip_advisor_rating", type="decimal", precision=12, scale=2, nullable=true)
     *
     * @Serializer\Expose
     */
    private $tripAdvisorRating;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ReputationData", mappedBy="reputation")
     * @Serializer\Expose
     **/
    private $reputationData;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ReputationSiteData", mappedBy="reputation")
     **/
    private $reputationSiteData;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ReputationReview", mappedBy="reputation")
     **/
    private $reviews;

    /**
     * @var string
     *
     * @ORM\Column(name="guid", type="string", nullable=true)
     */
    private $guid;

    /**
     * @var string
     *
     * @ORM\Column(name="trip_advisor_rank", type="string", nullable=true)
     */
    private $tripAdvisorRank;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ReputationCompetitor", mappedBy="reputation")
     **/
    private $competitors;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ReputationEmail", mappedBy="reputation")
     **/
    private $emails;

    /**
     * @var integer
     *
     * @ORM\Column(name="lifetime_sent", type="integer", nullable=true)
     */
    private $lifetimeSent;

    /**
     * @var integer
     *
     * @ORM\Column(name="lifetime_opened", type="integer", nullable=true)
     */
    private $lifetimeOpened;

    /**
     * @var integer
     *
     * @ORM\Column(name="lifetime_yes", type="integer", nullable=true)
     */
    private $lifetimeYes;

    /**
     * @var integer
     *
     * @ORM\Column(name="lifetime_no", type="integer", nullable=true)
     */
    private $lifetimeNo;

    /**
     * @var integer
     *
     * @ORM\Column(name="lifetime_redirects", type="integer", nullable=true)
     */
    private $lifetimeRedirects;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_upload", type="datetime", nullable=true)
     */
    private $lastUpload;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ReputationCustomer", mappedBy="reputation")
     **/
    private $customers;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_customers", type="integer", nullable=true)
     */
    private $totalCustomers;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ReputationSurvey", mappedBy="reputation")
     **/
    private $surveys;


    public function __construct()
    {
        $this->externalStars = [];
        $this->reputationData = new ArrayCollection();
        $this->reputationSiteData = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->competitors = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->customers = new ArrayCollection();
        $this->surveys = new ArrayCollection();
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
     * Set property
     *
     * @param Property $property
     * @return Reputation
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Reputation
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     *
     * @deprecated Use isActive() instead
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return Reputation
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
     * @return Reputation
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
     * Set externalAverageRating
     *
     * @param string $externalAverageRating
     * @return Reputation
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
     * @return Reputation
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
     * @return Reputation
     */
    public function setExternalStars(array $externalStars)
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
     * @return Reputation
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
     * @return Reputation
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
     * Set reputationData
     *
     * @param ArrayCollection $reputationData
     * @return Reputation
     */
    public function setReputationData(ArrayCollection $reputationData)
    {
        $this->reputationData = $reputationData;

        return $this;
    }

    /**
     * Get reputationData
     *
     * @return ArrayCollection
     */
    public function getReputationData()
    {
        return $this->reputationData;
    }

    /**
     * Add reputationData
     *
     * @param ReputationData $reputationData
     * @return Reputation
     */
    public function addReputationData(ReputationData $reputationData)
    {
        $this->reputationData[] = $reputationData;

        return $this;
    }

    /**
     * Remove reputationData
     *
     * @param ReputationData $reputationData
     * @return Reputation
     */
    public function removeReputationData(ReputationData $reputationData)
    {
        $this->reputationData->removeElement($reputationData);

        return $this;
    }

    /**
     * Has reputationData
     *
     * @param ReputationData $reputationData
     * @return boolean
     */
    public function hasReputationData(ReputationData $reputationData)
    {
        return $this->reputationData->contains($reputationData);
    }

    /**
     * Set reputationSiteData
     *
     * @param ArrayCollection $reputationSiteData
     * @return Reputation
     */
    public function setReputationSiteData(ArrayCollection $reputationSiteData)
    {
        $this->reputationSiteData = $reputationSiteData;

        return $this;
    }

    /**
     * Get reputationSiteData
     *
     * @return ArrayCollection
     */
    public function getReputationSiteData()
    {
        return $this->reputationSiteData;
    }

    /**
     * Add reputationSiteData
     *
     * @param ReputationSiteData $reputationSiteData
     * @return Reputation
     */
    public function addReputationSiteData(ReputationSiteData $reputationSiteData)
    {
        $this->reputationSiteData[] = $reputationSiteData;

        return $this;
    }

    /**
     * Remove reputationSiteData
     *
     * @param ReputationSiteData $reputationSiteData
     * @return Reputation
     */
    public function removeReputationSiteData(ReputationSiteData $reputationSiteData)
    {
        $this->reputationSiteData->removeElement($reputationSiteData);

        return $this;
    }

    /**
     * Has reputationSiteData
     *
     * @param ReputationSiteData $reputationSiteData
     * @return boolean
     */
    public function hasReputationSiteData(ReputationSiteData $reputationSiteData)
    {
        return $this->reputationSiteData->contains($reputationSiteData);
    }

    /**
     * Set guid
     *
     * @param string $guid
     *
     * @return Reputation
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set reviews
     *
     * @param ArrayCollection $reviews
     * @return Reputation
     */
    public function setReviews(ArrayCollection $reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * Get reviews
     *
     * @return ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Add review
     *
     * @param ReputationReview $review
     * @return Reputation
     */
    public function addReview(ReputationReview $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param ReputationReview $review
     * @return Reputation
     */
    public function removeReview(ReputationReview $review)
    {
        $this->reviews->removeElement($review);

        return $this;
    }

    /**
     * Has review
     *
     * @param ReputationReview $review
     * @return boolean
     */
    public function hasReview(ReputationReview $review)
    {
        return $this->reviews->contains($review);
    }

    /**
     * Set tripAdvisorRank
     *
     * @param string $tripAdvisorRank
     * @return Reputation
     */
    public function setTripAdvisorRank($tripAdvisorRank)
    {
        $this->tripAdvisorRank = $tripAdvisorRank;

        return $this;
    }

    /**
     * Get tripAdvisorRank
     *
     * @return string
     */
    public function getTripAdvisorRank()
    {
        return $this->tripAdvisorRank;
    }

    /**
     * Set competitors
     *
     * @param ArrayCollection $competitors
     * @return Reputation
     */
    public function setCompetitors(ArrayCollection $competitors)
    {
        $this->competitors = $competitors;

        return $this;
    }

    /**
     * Get competitors
     *
     * @return ArrayCollection
     */
    public function getCompetitors()
    {
        return $this->competitors;
    }

    /**
     * Add competitor
     *
     * @param ReputationCompetitor $competitor
     * @return Reputation
     */
    public function addCompetitor(ReputationCompetitor $competitor)
    {
        $this->competitors[] = $competitor;

        return $this;
    }

    /**
     * Remove competitor
     *
     * @param ReputationCompetitor $competitor
     * @return Reputation
     */
    public function removeCompetitor(ReputationCompetitor $competitor)
    {
        $this->competitors->removeElement($competitor);

        return $this;
    }

    /**
     * Has competitor
     *
     * @param ReputationCompetitor $competitor
     * @return boolean
     */
    public function hasCompetitor(ReputationCompetitor $competitor)
    {
        return $this->competitors->contains($competitor);
    }

    /**
     * Set emails
     *
     * @param ArrayCollection $emails
     * @return Reputation
     */
    public function setEmails(ArrayCollection $emails)
    {
        $this->emails = $emails;

        return $this;
    }

    /**
     * Get emails
     *
     * @return ArrayCollection
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Add email
     *
     * @param ReputationEmail $email
     * @return Reputation
     */
    public function addEmail(ReputationEmail $email)
    {
        $this->emails[] = $email;

        return $this;
    }

    /**
     * Remove email
     *
     * @param ReputationEmail $email
     * @return Reputation
     */
    public function removeEmail(ReputationEmail $email)
    {
        $this->emails->removeElement($email);

        return $this;
    }

    /**
     * Has email
     *
     * @param ReputationEmail $email
     * @return boolean
     */
    public function hasEmail(ReputationEmail $email)
    {
        return $this->emails->contains($email);
    }

    /**
     * Set lifetimeSent
     *
     * @param integer $lifetimeSent
     * @return Reputation
     */
    public function setLifetimeSent($lifetimeSent)
    {
        $this->lifetimeSent = $lifetimeSent;

        return $this;
    }

    /**
     * Get lifetimeSent
     *
     * @return integer
     */
    public function getLifetimeSent()
    {
        return $this->lifetimeSent;
    }

    /**
     * Set lifetimeOpened
     *
     * @param integer $lifetimeOpened
     * @return Reputation
     */
    public function setLifetimeOpened($lifetimeOpened)
    {
        $this->lifetimeOpened = $lifetimeOpened;

        return $this;
    }

    /**
     * Get lifetimeOpened
     *
     * @return integer
     */
    public function getLifetimeOpened()
    {
        return $this->lifetimeOpened;
    }

    /**
     * Set lifetimeYes
     *
     * @param integer $lifetimeYes
     * @return Reputation
     */
    public function setLifetimeYes($lifetimeYes)
    {
        $this->lifetimeYes = $lifetimeYes;

        return $this;
    }

    /**
     * Get lifetimeYes
     *
     * @return integer
     */
    public function getLifetimeYes()
    {
        return $this->lifetimeYes;
    }

    /**
     * Set lifetimeNo
     *
     * @param integer $lifetimeNo
     * @return Reputation
     */
    public function setLifetimeNo($lifetimeNo)
    {
        $this->lifetimeNo = $lifetimeNo;

        return $this;
    }

    /**
     * Get lifetimeNo
     *
     * @return integer
     */
    public function getLifetimeNo()
    {
        return $this->lifetimeNo;
    }

    /**
     * Set lifetimeRedirects
     *
     * @param integer $lifetimeRedirects
     * @return Reputation
     */
    public function setLifetimeRedirects($lifetimeRedirects)
    {
        $this->lifetimeRedirects = $lifetimeRedirects;

        return $this;
    }

    /**
     * Get lifetimeRedirects
     *
     * @return integer
     */
    public function getLifetimeRedirects()
    {
        return $this->lifetimeRedirects;
    }

    /**
     * Set lastUpload
     *
     * @param DateTime $lastUpload
     *
     * @return Reputation
     */
    public function setLastUpload($lastUpload)
    {
        $this->lastUpload = $lastUpload;

        return $this;
    }

    /**
     * Get lastUpload
     *
     * @return DateTime
     */
    public function getLastUpload()
    {
        return $this->lastUpload;
    }

    /**
     * Set customers
     *
     * @param ArrayCollection $customers
     * @return Reputation
     */
    public function setCustomers(ArrayCollection $customers)
    {
        $this->customers = $customers;

        return $this;
    }

    /**
     * Get customers
     *
     * @return ArrayCollection
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * Add customer
     *
     * @param ReputationCustomer $customer
     * @return Reputation
     */
    public function addCustomer(ReputationCustomer $customer)
    {
        $this->customers[] = $customer;

        return $this;
    }

    /**
     * Remove customer
     *
     * @param ReputationCustomer $customer
     * @return Reputation
     */
    public function removeCustomer(ReputationCustomer $customer)
    {
        $this->customers->removeElement($customer);

        return $this;
    }

    /**
     * Has customer
     *
     * @param ReputationCustomer $customer
     * @return boolean
     */
    public function hasCustomer(ReputationCustomer $customer)
    {
        return $this->customers->contains($customer);
    }

    /**
     * Set totalCustomers
     *
     * @param integer $totalCustomers
     * @return Reputation
     */
    public function setTotalCustomers($totalCustomers)
    {
        $this->totalCustomers = $totalCustomers;

        return $this;
    }

    /**
     * Get totalCustomers
     *
     * @return integer
     */
    public function getTotalCustomers()
    {
        return $this->totalCustomers;
    }

    /**
     * Set surveys
     *
     * @param ArrayCollection $surveys
     * @return Reputation
     */
    public function setSurveys(ArrayCollection $surveys)
    {
        $this->surveys = $surveys;

        return $this;
    }

    /**
     * Get surveys
     *
     * @return ArrayCollection
     */
    public function getSurveys()
    {
        return $this->surveys;
    }

    /**
     * Add survey
     *
     * @param ReputationSurvey $survey
     * @return Reputation
     */
    public function addSurvey(ReputationSurvey $survey)
    {
        $this->surveys[] = $survey;

        return $this;
    }

    /**
     * Remove survey
     *
     * @param ReputationSurvey $survey
     * @return Reputation
     */
    public function removeSurvey(ReputationSurvey $survey)
    {
        $this->surveys->removeElement($survey);

        return $this;
    }

    /**
     * Has survey
     *
     * @param ReputationSurvey $survey
     * @return boolean
     */
    public function hasSurvey(ReputationSurvey $survey)
    {
        return $this->surveys->contains($survey);
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
}
