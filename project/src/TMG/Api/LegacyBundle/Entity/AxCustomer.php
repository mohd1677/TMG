<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class AxCustomer extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $customerNumber;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $legacyCustomerNumber;

    /**
     * @ORM\OneToMany(targetEntity="AxOrder", mappedBy="customer", cascade={"persist"})
     */
    protected $orders;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $contactName;

    /**
     * @ORM\ManyToOne(targetEntity="AxCustomerAddress", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id",  nullable=true)
     * @Assert\Valid
     */
    protected $address;

    /**
     * @ORM\ManyToOne(targetEntity="AxCustomerAddress", cascade={"persist"})
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id",  nullable=true)
     * @Assert\Valid
     */
    protected $billingAddress;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fax;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $sendFax;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $sendEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * AxCustomer constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
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
     * @return AxCustomer
     */
    public function setId($v)
    {
        $this->id = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerNumber()
    {
        return $this->customerNumber;
    }

    /**
     * @param string $v
     *
     * @return AxCustomer
     */
    public function setCustomerNumber($v)
    {
        $this->customerNumber = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getLegacyCustomerNumber()
    {
        return $this->legacyCustomerNumber;
    }

    /**
     * @param string $v
     *
     * @return AxCustomer
     */
    public function setLegacyCustomerNumber($v)
    {
        $this->legacyCustomerNumber = $v;
        return $this;
    }

    /**
     * @return AxOrder
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param AxOrder $v
     *
     * @return AxCustomer
     */
    public function addOrder(AxOrder $v)
    {
        $this->orders->add($v);
        return $this;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return AxCustomer
     */
    public function setOrders(ArrayCollection $v)
    {
        $this->orders = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $v
     *
     * @return AxCustomer
     */
    public function setName($v)
    {
        $this->name = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $v
     *
     * @return AxCustomer
     */
    public function setContactName($v)
    {
        $this->contactName = $v;
        return $this;
    }

    /**
     * @return AxAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param AxAddress $v
     *
     * @return AxCustomer
     */
    public function setAddress($v = null)
    {
        $this->address = $v;
        return $this;
    }

    /**
     * @return AxAddress
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param AxAddress $v
     *
     * @return AxCustomer
     */
    public function setBillingAddress($v = null)
    {
        $this->billingAddress = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $v
     *
     * @return AxCustomer
     */
    public function setEmail($v)
    {
        $this->email = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $v
     *
     * @return AxCustomer
     */
    public function setFax($v)
    {
        $this->fax = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $v
     *
     * @return AxCustomer
     */
    public function setPhone($v)
    {
        $this->phone = $v;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSendFax()
    {
        return $this->sendFax;
    }

    /**
     * @param bool $v
     *
     * @return AxCustomer
     */
    public function setSendFax($v)
    {
        $this->sendFax = $v;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSendEmail()
    {
        return $this->sendEmail;
    }

    /**
     * @param bool $v
     *
     * @return AxCustomer
     */
    public function setSendEmail($v)
    {
        $this->sendEmail = $v;
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
     * @return AxCustomer
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
     * @return AxCustomer
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
