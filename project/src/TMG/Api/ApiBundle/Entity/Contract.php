<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Contract
 *
 * @ORM\Table(name="Contracts")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ContractRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Contract
{
    public function __construct()
    {
        $this->confirmations = new ArrayCollection();
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
     * @ORM\ManyToOne(targetEntity="Books", inversedBy="contracts")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     **/
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity="Products", inversedBy="contracts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "feedback"})
     **/
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="contracts")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "feedback"})
     **/
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity="SalesRep")
     * @ORM\JoinColumn(name="rep_id", referencedColumnName="id")
     **/
    private $rep;

    /**
     * @ORM\OneToMany(targetEntity="Confirmation", mappedBy="contract")
     **/
    private $confirmations;

    /**
     * @var boolean
     *
     * @ORM\Column(name="current_active", type="boolean")
     */
    private $currentActive;

    /**
     * @var string
     *
     * @ORM\Column(name="space_reserved", type="string", length=255, nullable=true)
     */
    private $spaceReserved;

    /**
     * @var string
     *
     * @ORM\Column(name="collection_message", type="string", length=255, nullable=true)
     */
    private $collectionMessage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     *
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "feedback"})
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     *
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "feedback"})
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="start_issue", type="integer", length=4)
     */
    private $startIssue;

    /**
     * @var integer
     *
     * @ORM\Column(name="end_issue", type="integer", length=4)
     */
    private $endIssue;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_copy", type="boolean")
     */
    private $emailCopy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fax_copy", type="boolean")
     */
    private $faxCopy;

    /**
     * @var string
     *
     * @ORM\Column(name="feed_status", type="string", length=10)
     */
    private $feedStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="order_number", type="string", length=255)
     */
    private $orderNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="master_order_number", type="string", length=255, nullable=true)
     */
    private $masterOrderNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="master_order_account", type="string", length=255, nullable=true)
     */
    private $masterOrderAccount;

    /**
     * @var string
     *
     * @ORM\Column(name="master_order_e1_account", type="string", length=255, nullable=true)
     */
    private $masterOrderE1Account;

    /**
     * @var string
     *
     * @ORM\Column(name="lisfid", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "feedback"})
     */
    private $lisfid;

    /**
     * @var string
     *
     * @ORM\Column(name="verecid", type="string", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "feedback"})
     */
    private $verecid;

    /**
     * Indicates whether the contract has the Auto-Renew or Evergreen features.
     * Digital products will be Evergreen, while print products will be Auto-Renew
     * if the asset was created after the program was rolled out.
     *
     * 0 = N/A
     * 1 = Auto-Renew
     * 2 = Evergreen
     *
     * @var integer
     *
     * @ORM\Column(name="auto_renew_option", type="integer", nullable=true);
     */
    protected $autoRenewOption;

    /**
     * Indicates whether the contract should be active or not.
     *
     * 0 = Created
     * 1 = BillingRegistered
     * 2 = RevenueScheduled
     * 3 = ReservedForFuture
     * 4 = Cancelled
     * 5 = Stopped
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true);
     */
    protected $veStatus;

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
     * Set book
     *
     * @param Books $book
     *
     * @return Contract
     */
    public function setBook(Books $book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set product
     *
     * @param Products $product
     *
     * @return Contract
     */
    public function setProduct(Products $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set property
     *
     * @param Property $property
     *
     * @return Contract
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
     * Set rep
     *
     * @param SalesRep $rep
     *
     * @return Contract
     */
    public function setRep(SalesRep $rep)
    {
        $this->rep = $rep;

        return $this;
    }

    /**
     * Get rep
     *
     */
    public function getRep()
    {
        return $this->rep;
    }

    /**
     * Set confirmations
     *
     * @param ArrayCollection $confirmations
     * @return Contract
     */
    public function setConfirmations(ArrayCollection $confirmations)
    {
        $this->confirmations = $confirmations;

        return $this;
    }

    /**
     * Get confirmations
     *
     * @return string
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }

    /**
     * Add Confirmation
     *
     * @param Confirmation $confirmation
     * @return Contract
     */
    public function addConfirmation(Confirmation $confirmation)
    {
        $this->confirmations[] = $confirmation;

        return $this;
    }

    /**
     * Remove Confirmation
     *
     * @param Confirmation $confirmation
     * @return Contract
     */
    public function removeConfirmation(Confirmation $confirmation)
    {
        $this->confirmations->removeElement($confirmation);

        return $this;
    }

    /**
     * Has Confirmation
     *
     * @param Confirmation $confirmation
     * @return boolean
     */
    public function hasConfirmation(Confirmation $confirmation)
    {
        return $this->confirmations->contains($confirmation);
    }

    /**
     * Set currentActive
     *
     * @param boolean $currentActive
     * @return Contract
     */
    public function setCurrentActive($currentActive)
    {
        $this->currentActive = $currentActive;

        return $this;
    }

    /**
     * Get currentActive
     *
     * @return boolean
     */
    public function getCurrentActive()
    {
        return $this->currentActive;
    }

    /**
     * Set spaceReserved
     *
     * @param string $spaceReserved
     * @return Contract
     */
    public function setSpaceReserved($spaceReserved)
    {
        $this->spaceReserved = $spaceReserved;

        return $this;
    }

    /**
     * Get spaceReserved
     *
     * @return string
     */
    public function getSpaceReserved()
    {
        return $this->spaceReserved;
    }

    /**
     * Set collectionMessage
     *
     * @param string $collectionMessage
     * @return Contract
     */
    public function setCollectionMessage($collectionMessage)
    {
        $this->collectionMessage = $collectionMessage;

        return $this;
    }

    /**
     * Get collectionMessage
     *
     * @return string
     */
    public function getCollectionMessage()
    {
        return $this->collectionMessage;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Contract
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        $this->startIssue = $this->formatYRMO($startDate);

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Contract
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        $this->endIssue = $this->formatYRMO($endDate);

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set startIssue
     *
     * @param integer $startIssue
     * @return Contract
     */
    public function setStartIssue($startIssue)
    {
        $this->startIssue = $this->formatYRMO($startIssue);

        return $this;
    }

    /**
     * Get startIssue
     *
     * @return integer
     */
    public function getStartIssue()
    {
        return $this->startIssue;
    }

    /**
     * Set endIssue
     *
     * @param integer $endIssue
     * @return Contract
     */
    public function setEndIssue($endIssue)
    {
        $this->endIssue = $this->formatYRMO($endIssue);

        return $this;
    }

    /**
     * Get endIssue
     *
     * @return integer
     */
    public function getEndIssue()
    {
        return $this->endIssue;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Contract
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Contract
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set emailCopy
     *
     * @param boolean $emailCopy
     * @return Contract
     */
    public function setEmailCopy($emailCopy)
    {
        $this->emailCopy = $emailCopy;

        return $this;
    }

    /**
     * Get emailCopy
     *
     * @return boolean
     */
    public function getEmailCopy()
    {
        return $this->emailCopy;
    }

    /**
     * Set faxCopy
     *
     * @param boolean $faxCopy
     * @return Contract
     */
    public function setFaxCopy($faxCopy)
    {
        $this->faxCopy = $faxCopy;

        return $this;
    }

    /**
     * Get faxCopy
     *
     * @return boolean
     */
    public function getFaxCopy()
    {
        return $this->faxCopy;
    }

    /**
     * Set feedStatus
     *
     * @param string $feedStatus
     * @return Contract
     */
    public function setFeedStatus($feedStatus)
    {
        $this->feedStatus = $feedStatus;

        return $this;
    }

    /**
     * Get feedStatus
     *
     * @return string
     */
    public function getFeedStatus()
    {
        return $this->feedStatus;
    }

    /**
     * Set orderNumber
     *
     * @param string $orderNumber
     * @return Contract
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set masterOrderNumber
     *
     * @param string $masterOrderNumber
     * @return Contract
     */
    public function setMasterOrderNumber($masterOrderNumber)
    {
        $this->masterOrderNumber = $masterOrderNumber;

        return $this;
    }

    /**
     * Get masterOrderNumber
     *
     * @return string
     */
    public function getMasterOrderNumber()
    {
        return $this->masterOrderNumber;
    }

    /**
     * Set masterOrderAccount
     *
     * @param string $masterOrderAccount
     * @return Contract
     */
    public function setMasterOrderAccount($masterOrderAccount)
    {
        $this->masterOrderAccount = $masterOrderAccount;

        return $this;
    }

    /**
     * Get masterOrderAccount
     *
     * @return string
     */
    public function getMasterOrderAccount()
    {
        return $this->masterOrderAccount;
    }

    /**
     * Set masterOrderE1Account
     *
     * @param string $masterOrderE1Account
     * @return Contract
     */
    public function setMasterOrderE1Account($masterOrderE1Account)
    {
        $this->masterOrderE1Account = $masterOrderE1Account;

        return $this;
    }

    /**
     * Get masterOrderE1Account
     *
     * @return string
     */
    public function getMasterOrderE1Account()
    {
        return $this->masterOrderE1Account;
    }

    /**
     * Set lisfid
     *
     * @param string $lisfid
     * @return Contract
     */
    public function setLisfid($lisfid)
    {
        $this->lisfid = $lisfid;

        return $this;
    }

    /**
     * Get lisfid
     *
     * @return string
     */
    public function getLisfid()
    {
        return $this->lisfid;
    }

    /**
     * Set VERECID
     *
     * @param string $verecid
     * @return Contract
     */
    public function setVerecid($verecid)
    {
        $this->verecid = $verecid;

        return $this;
    }

    /**
     * Get VERECID
     *
     * @return string
     */
    public function getVerecid()
    {
        return $this->verecid;
    }

    /**
     * Get Auto-Renew Option
     *
     * @return integer 0 = N/A, 1 = Auto-Renew, 2 = Evergreen
     */
    public function getAutoRenewOption()
    {
        return $this->autoRenewOption;
    }

    /**
     * Set Auto-Renew Option
     *
     * @param integer $v 0 = N/A, 1 = Auto-Renew, 2 = Evergreen
     *
     * @return Contract
     */
    public function setAutoRenewOption($v)
    {
        $this->autoRenewOption = $v;

        return $this;
    }

    /**
     * Get VE Status
     *
     * @return integer
     *         0 = Created,
     *         1 = BillingRegistered,
     *         2 = RevenueScheduled,
     *         3 = ReservedForFuture,
     *         4 = Cancelled,
     *         5 = Stopped
     */
    public function getVeStatus()
    {
        return $this->veStatus;
    }

    /**
     * Set VE Status
     *
     * @param integer $v
     *        0 = Created,
     *        1 = BillingRegistered,
     *        2 = RevenueScheduled,
     *        3 = ReservedForFuture,
     *        4 = Cancelled,
     *        5 = Stopped
     *
     * @return Contract
     */
    public function setVeStatus($v)
    {
        $this->veStatus = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Contract
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
     * @return Contract
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
    private function formatYRMO($date)
    {
        if ($date instanceof \DateTime) {
            $date = $date->format('ym');

            return (int)$date;
        } else {
            $date = new \DateTime($date);
            $date = $date->format('ym');

            return (int)$date;
        }
    }
}
