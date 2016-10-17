<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A contract tying a property to a service
 *
 * @ORM\Entity
 */
class Contract extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Does the current property have
     *
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * Which order a contract came from. An order may contain multiple contracts.
     *
     * @ORM\Column(type="string")
     */
    protected $orderNumber;

    /**
     * Date of service start
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date()
     */
    protected $startDate;

    /**
     * Date of service end
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date()
     */
    protected $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="ContractType")
     * @ORM\JoinColumn(name="contract_type_code", referencedColumnName="code",  nullable=false)
     *
     * @Assert\Valid
     */
    protected $contractType;

    /**
     * @ORM\ManyToOne(targetEntity="SalesRep", cascade={"persist"})
     * @ORM\JoinColumn(name="sales_rep_code", referencedColumnName="code",  nullable=false)
     *
     * @Assert\Valid
     */
    protected $salesRep;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="contracts", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\Valid
     */
    protected $property;

    /**
     * @ORM\OneToMany(targetEntity="ContractInactiveMonth", mappedBy="contract")
     *
     * @Assert\Valid
     */
    protected $inactiveMonths;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $collectionMessage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $fromAx;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Contract constructor.
     */
    public function __construct()
    {
        $this->inactiveMonths = new ArrayCollection;
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
     * @return Contract
     */
    public function setId($v)
    {
        $this->id = $v;

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
     * @return Contract
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
        $yrmo = $when->format('ym');

        /**
         * @param $key
         * @param ContractInactiveMonth $cim
         *
         * @return bool
         */
        $isMatchingYrmo = function ($key, $cim) use ($yrmo) {
            return $cim->getYrmo() == $yrmo;
        };

        return $this->startDate < $when
            && $this->endDate > $when
            && !$this->inactiveMonths->exists($isMatchingYrmo);
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $v
     *
     * @return Contract
     */
    public function setOrderNumber($v)
    {
        $this->orderNumber = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $v
     *
     * @return Contract
     */
    public function setStartDate(DateTime $v)
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
     * @param DateTime $v
     *
     * @return Contract
     */
    public function setEndDate(DateTime $v)
    {
        $this->endDate = $v;

        return $this;
    }

    /**
     * @return ContractType
     */
    public function getContractType()
    {
        return $this->contractType;
    }

    /**
     * @param ContractType $v
     *
     * @return Contract
     */
    public function setContractType(ContractType $v)
    {
        $this->contractType = $v;

        return $this;
    }

    /**
     * @return SalesRep
     */
    public function getSalesRep()
    {
        return $this->salesRep;
    }

    /**
     * @param SalesRep $v
     *
     * @return Contract
     */
    public function setSalesRep(SalesRep $v)
    {
        $this->salesRep = $v;

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
     * @return Contract
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getInactiveMonths()
    {
        return $this->inactiveMonths;
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
     * @return Contract
     */
    public function setCollectionMessage($v)
    {
        $this->collectionMessage = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $v
     *
     * @return Contract
     */
    public function setStatus($v)
    {
        $this->status = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getFromAx()
    {
        return $this->fromAx;
    }

    /**
     * @param bool $v
     *
     * @return Contract
     */
    public function setFromAx($v)
    {
        $this->fromAx = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Contract
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     * @return Contract
     */
    public function setUpdatedAt(\DateTime $updatedAt)
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
}
