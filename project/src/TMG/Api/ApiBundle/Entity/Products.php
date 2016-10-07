<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use JMS\Serializer\Annotation as Serializer;

/**
 * Products
 *
 * @ORM\Table(name="Products")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ProductsRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Products
{
    public function __construct()
    {
        $this->contracts = new ArrayCollection();
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=55)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "feedback"})
     */
    private $code;

    /**
     * @var ProductTypes
     *
     * @ORM\ManyToOne(targetEntity="ProductTypes", cascade={"persist"})
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;

    /**
     * @var SpecialType
     *
     * @ORM\ManyToOne(targetEntity="SpecialType", cascade={"persist"})
     * @ORM\JoinColumn(name="special", referencedColumnName="id", nullable=true)
     */
    private $special;

    /**
     * @var string
     *
     * @ORM\Column(name="type_description", type="string", length=255, nullable=true)
     */
    private $typeDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="ad_size", type="string", length=55, nullable=true)
     */
    private $adSize;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="product")
     **/
    private $contracts;

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
     * Set code
     *
     * @param string $code
     * @return Products
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type
     *
     * @param ProductTypes $type
     * @return Products
     */
    public function setType(ProductTypes $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set special
     *
     * @param SpecialType $special
     * @return Products
     */
    public function setSpecial(SpecialType $special)
    {
        $this->special = $special;

        return $this;
    }

    /**
     * Get special
     *
     * @return string
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * Set typeDescription
     *
     * @param string $typeDescription
     * @return Products
     */
    public function setTypeDescription($typeDescription)
    {
        $this->typeDescription = $typeDescription;

        return $this;
    }

    /**
     * Get typeDescription
     *
     * @return string
     */
    public function getTypeDescription()
    {
        return $this->typeDescription;
    }

    /**
     * Set adSize
     *
     * @param string $adSize
     * @return Products
     */
    public function setAdSize($adSize)
    {
        $this->adSize = $adSize;

        return $this;
    }

    /**
     * Get adSize
     *
     * @return string
     */
    public function getAdSize()
    {
        return $this->adSize;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Products
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set contracts
     *
     * @param ArrayCollection $contracts
     * @return Products
     */
    public function setContracts(ArrayCollection $contracts)
    {
        $this->contracts = $contracts;

        return $this;
    }

    /**
     * Get contracts
     *
     * @return string
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Add Contract
     *
     * @param Contract
     * @return Products
     */
    public function addContract(Contract $v)
    {
        $this->contracts[] = $v;

        return $this;
    }

    /**
     * Remove Contract
     *
     * @param Contract
     * @return Products
     */
    public function removeContract(Contract $v)
    {
        $this->contracts->removeElement($v);

        return $this;
    }

    /**
     * Has Contract
     *
     * @param Contract
     * @return boolean
     */
    public function hasContract(Contract $v)
    {
        return $this->contracts->contains($v);
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Products
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
     * @return Products
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
}
