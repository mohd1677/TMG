<?php

namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A contract tying a property to a service
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class AxItem extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $masterOrderNumber;

    /**
     * @ORM\ManyToOne(targetEntity="AxOrder", inversedBy="items", cascade={"persist"})
     * @ORM\JoinColumn(name="order_number_id", referencedColumnName="id", nullable=true)
     */
    protected $orderNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $itemCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $itemType;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $print;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $internet;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $eightHundred;

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
     * @ORM\Column(type="datetime")
     */
    protected $startDate;

    /**
     * Date of service end
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
     * @ORM\OneToMany(targetEntity="AxIssue", mappedBy="itemNumber", cascade={"persist"})
     */
    protected $issues;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $department;

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
     * AxItem constructor.
     */
    public function __construct()
    {
        $this->issues = new ArrayCollection();
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
     * @return AxItem
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
     * @return AxItem
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
     * @return AxItem
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
     * @return AxItem
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
     * @return AxItem
     */
    public function setItemType($v)
    {
        $this->itemType = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPrint()
    {
        return $this->print;
    }

    /**
     * @param bool $v
     *
     * @return AxItem
     */
    public function setPrint($v)
    {
        $this->print = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getInternet()
    {
        return $this->internet;
    }

    /**
     * @param bool $v
     *
     * @return AxItem
     */
    public function setInternet($v)
    {
        $this->internet = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEightHundred()
    {
        return $this->eightHundred;
    }

    /**
     * @param bool $v
     *
     * @return AxItem
     */
    public function setEightHundred($v)
    {
        $this->eightHundred = $v;

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
     * @return AxItem
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
     * @return AxItem
     */
    public function setCollectionMessage($v)
    {
        $this->collectionMessage = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $v
     *
     * @return AxItem
     */
    public function setIsActive($v)
    {
        $this->isActive = $v;

        return $this;
    }

    /**
     * @param DateTime $when
     *
     * @return bool
     */
    public function isActiveForDate(DateTime $when)
    {
        return $this->startDate < $when && $this->endDate > $when;
    }

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $v
     *
     * @return AxItem
     */
    public function setStartDate(DateTime $v)
    {
        $this->startDate = $v;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $v
     *
     * @return AxItem
     */
    public function setEndDate(DateTime $v)
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
     * @return AxItem
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
     * @return AxItem
     */
    public function setEndIssue($v)
    {
        $this->endIssue = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @param AxIssue $v
     *
     * @return AxItem
     */
    public function addIssue(AxIssue $v)
    {
        $this->issues->add($v);

        return $this;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return AxItem
     */
    public function setIssues(ArrayCollection $v)
    {
        $this->issues = $v;

        return $this;
    }

    /**
     * @param int $issueNumber
     *
     * @return AxIssue
     */
    public function getCurrentIssue($issueNumber)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("issueNumber", $issueNumber));

        return $this->issues->matching($criteria)->first();
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
     * @return AxItem
     */
    public function setDepartment($v)
    {
        $this->department = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return AxItem
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return AxItem
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
