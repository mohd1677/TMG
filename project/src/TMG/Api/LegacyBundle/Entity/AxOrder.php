<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class AxOrder extends AbstractEntity
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
    protected $orderNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $legacyOrderNumber;

    /**
     * @ORM\ManyToOne(targetEntity="SalesRep", cascade={"persist"})
     * @ORM\JoinColumn(name="sales_rep_code", referencedColumnName="code",  nullable=false)
     *
     * @Assert\Valid
     */
    protected $salesRep;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\ManyToOne(targetEntity="AxCustomer", inversedBy="orders", cascade={"persist"})
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=true)
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="AxItem", mappedBy="orderNumber", cascade={"persist"})
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="AxContract", mappedBy="orderNumber", cascade={"persist"})
     */
    protected $contracts;

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
     * AxOrder constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->contracts = new ArrayCollection();
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
     * @return AxOrder
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
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
     * @return AxOrder
     */
    public function setOrderNumber($v)
    {
        $this->orderNumber = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getLegacyOrderNumber()
    {
        return $this->legacyOrderNumber;
    }

    /**
     * @param string $v
     *
     * @return AxOrder
     */
    public function setLegacyOrderNumber($v)
    {
        $this->legacyOrderNumber = $v;

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
     * @return AxOrder
     */
    public function setSalesRep(SalesRep $v)
    {
        $this->salesRep = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $v
     *
     * @return AxOrder
     */
    public function setCity($v)
    {
        $this->city = $v;

        return $this;
    }

    /**
     * @return AxCustomer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param AxCustomer $v
     *
     * @return AxOrder
     */
    public function setCustomer(AxCustomer $v)
    {
        $this->customer = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return AxOrder
     */
    public function setItems(ArrayCollection $v)
    {
        $this->items = $v;

        return $this;
    }

    /**
     * @param AxItem $v
     *
     * @return AxOrder
     */
    public function addItem(AxItem $v)
    {
        $this->items->add($v);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return AxOrder
     */
    public function setContracts(ArrayCollection $v)
    {
        $this->contracts = $v;

        return $this;
    }

    /**
     * @param AxContract $v
     *
     * @return AxOrder
     */
    public function addContract(AxContract $v)
    {
        $this->contracts->add($v);

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AxOrder
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
     * @return AxOrder
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
