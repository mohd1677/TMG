<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A contract tying a property to a service
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\AxContractRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AxContract extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AxOrder", inversedBy="contracts", cascade={"persist"})
     * @ORM\JoinColumn(name="order_number_id", referencedColumnName="id", nullable=true)
     */
    protected $orderNumber;

    /**
     * @ORM\ManyToOne(targetEntity="AxBook", inversedBy="contracts", cascade={"persist"})
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=true)
     */
    protected $book;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $masterOrderNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $itemCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $itemType;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $category;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $collectionMessage;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * Date of service start
     *
     * @ORM\Column(type="datetime")
     */
    protected $startDate;

    /**
     * Date of service end
     *
     * @ORM\Column(type="datetime")
     */
    protected $endDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $startIssue;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $endIssue;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $department;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $spaceReserved;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $adSize;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $color;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $position;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $emailCopy;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $faxCopy;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $eightHundredNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $verecid;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="contracts", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", nullable=true)
     *
     * @Assert\Valid
     */
    protected $property;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $axAccountNumber;

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
     * @ORM\Column(type="integer", nullable=true);
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
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="AxConfirmation", mappedBy="contract", cascade={"persist"})
     */
    protected $confirmations;

    /**
     * AxContract constructor.
     */
    public function __construct()
    {
        $this->confirmations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     *
     * @return AxContract
     */
    public function setId($v)
    {
        $this->id = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getMasterOrderNumber()
    {
        return $this->masterOrderNumber;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setMasterOrderNumber($v)
    {
        $this->masterOrderNumber = $v;
        return $this;
    }

    /**
     * @return AxOrder
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param AxOrder $v
     *
     * @return AxContract
     */
    public function setOrderNumber(AxOrder $v)
    {
        $this->orderNumber = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemCode()
    {
        return $this->itemCode;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setItemCode($v)
    {
        $this->itemCode = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemType()
    {
        return $this->itemType;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setItemType($v)
    {
        $this->itemType = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setCategory($v)
    {
        $this->category = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setStatus($v)
    {
        $this->status = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollectionMessage()
    {
        return $this->collectionMessage;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setCollectionMessage($v)
    {
        $this->collectionMessage = $v;
        return $this;
    }

    /**
     * Use isActive() instead
     *
     * @deprecated
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * True if the contract is currently active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $v
     *
     * @return AxContract
     */
    public function setIsActive($v)
    {
        $this->isActive = $v;
        return $this;
    }

    /**
     * @param \DateTime $when
     *
     * @return bool
     */
    public function isActiveForDate(\DateTime $when)
    {
        return $this->startDate < $when
            && $this->endDate > $when;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $v
     *
     * @return AxContract
     */
    public function setStartDate(\DateTime $v)
    {
        $this->startDate = $v;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $v
     *
     * @return AxContract
     */
    public function setEndDate(\DateTime $v)
    {
        $this->endDate = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartIssue()
    {
        return $this->startIssue;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setStartIssue($v)
    {
        $this->startIssue = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndIssue()
    {
        return $this->endIssue;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setEndIssue($v)
    {
        $this->endIssue = $v;
        return $this;
    }

    /**
     * @return arrayCollection
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }

    /**
     * @param AxConfirmation $v
     *
     * @return AxContract
     */
    public function addConfirmation(AxConfirmation $v)
    {
        $this->confirmations->add($v);
        return $this;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return AxContract
     */
    public function setConfirmations(ArrayCollection $v)
    {
        $this->confirmations = $v;
        return $this;
    }

    /**
     * @param string $issueNumber
     *
     * @return AxConfirmation
     */
    public function getCurrentConfirmation($issueNumber)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("issueNumber", $issueNumber));

        return $this->confirmations->matching($criteria)->first();
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setDepartment($v)
    {
        $this->department = $v;
        return $this;
    }

    /**
     * @return AxBook
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param AxBook $v
     *
     * @return AxContract
     */
    public function setBook(AxBook $v)
    {
        $this->book = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpaceReserved()
    {
        return $this->spaceReserved;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setSpaceReserved($v)
    {
        $this->spaceReserved = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdSize()
    {
        return $this->adSize;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setAdSize($v)
    {
        $this->adSize = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setColor($v)
    {
        $this->color = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setPosition($v)
    {
        $this->position = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setDescription($v)
    {
        $this->description = $v;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEmailCopy()
    {
        return $this->emailCopy;
    }

    /**
     * @param bool $v
     *
     * @return AxContract
     */
    public function setEmailCopy($v)
    {
        $this->emailCopy = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getFaxCopy()
    {
        return $this->faxCopy;
    }

    /**
     * @param bool $v
     *
     * @return AxContract
     */
    public function setFaxCopy($v)
    {
        $this->faxCopy = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getEightHundredNumber()
    {
        return $this->eightHundredNumber;
    }

    /**
     * @param string $v
     *
     * @return AxContract
     */
    public function setEightHundredNumber($v)
    {
        $this->eightHundredNumber = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getVerecid()
    {
        return $this->verecid;
    }

    /**
     * @param string $verecid
     *
     * @return AxContract
     */
    public function setVerecid($verecid)
    {
        $this->verecid = $verecid;

        return $this;
    }

    /**
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param Property $v
     *
     * @return AxContract
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;
        return $this;
    }

    /**
     * Get AX account number
     *
     * @return integer
     */
    public function getAxAccountNumber()
    {
        return $this->axAccountNumber;
    }

    /**
     * Set AX account number
     *
     * @param integer $v
     *
     * @return AxContract
     */
    public function setAxAccountNumber($v)
    {
        $this->axAccountNumber = $v;

        return $this;
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
     * @return AxContract
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
     * @return AxContract
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
     * @return AxContract
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return AxContract
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
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }
}
