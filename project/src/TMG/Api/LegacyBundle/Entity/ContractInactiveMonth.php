<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a single month & year that a contract is inactive for. A one-year
 * contract may actually span for 13 months of a single month inside that span
 * is inactive.
 *
 * @ORM\Entity
 */
class ContractInactiveMonth extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Contract", inversedBy="inactiveMonths", cascade={"persist"})
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\Valid
     */
    protected $contract;

    /**
     * A 4 digit year and month combination.
     * ex: 1304 for April, 2013
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank()
     */
    protected $yrmo;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     *
     * @return ContractInactiveMonth
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param Contract $v
     *
     * @return ContractInactiveMonth
     */
    public function setContract(Contract $v)
    {
        $this->contract = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getYrmo()
    {
        return $this->yrmo;
    }

    /**
     * @param int $v
     *
     * @return ContractInactiveMonth
     */
    public function setYrmo($v)
    {
        $this->yrmo = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ContractInactiveMonth
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
     * @return ContractInactiveMonth
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
