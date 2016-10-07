<?php

namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="social_data")
 * @ORM\HasLifecycleCallbacks()
 */
class Social extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $accountNumber;

    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $yrmo;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $network;

    /**
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $fans;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;


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
     * @return Social
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string $v
     *
     * @return Social
     */
    public function setAccountNumber($v)
    {
        $this->accountNumber = $v;

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
     * @return Social
     */
    public function setYrmo($v)
    {
        $this->yrmo = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param string $v
     *
     * @return Social
     */
    public function setNetwork($v)
    {
        $this->network = $v;
        return $this;
    }

    /**
     * @return int
     */
    public function getFans()
    {
        return $this->fans;
    }

    /**
     * @param int $v
     *
     * @return Social
     */
    public function setFans($v)
    {
        $this->fans = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Social
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
     * @return Social
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
