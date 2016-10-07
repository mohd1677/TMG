<?php

namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class AxConfirmation extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AxContract", inversedBy="confirmations", cascade={"persist"})
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id", nullable=true)
     */
    protected $contract;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $confirmed;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $updatedBy;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     *
     * @return AxConfirmation
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return AxContract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param AxContract $v
     *
     * @return AxConfirmation
     */
    public function setContract(AxContract $v)
    {
        $this->contract = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param string $v
     *
     * @return AxConfirmation
     */
    public function setConfirmed($v)
    {
        $this->confirmed = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param string $v
     *
     * @return AxConfirmation
     */
    public function setUpdatedBy($v)
    {
        $this->updatedBy = $v;

        return $this;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return AxConfirmation
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
     *
     * @return AxConfirmation
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
