<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use TMG\Api\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * Resolve Contractor Invoice
 *
 * @ORM\Table(name="ResolveContractorInvoice")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ResolveContractorInvoiceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("all")
 */
class ResolveContractorInvoice
{
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
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     *
     * @Serializer\Expose
     */
    private $hash;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\UserBundle\Entity\User",
     *     inversedBy="resolveContractorInvoices", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $user;

    /**
     * @var ResolveResponseRating
     *
     * @ORM\OneToMany(targetEntity="TMG\Api\ApiBundle\Entity\ResolveResponseRating",
     *     mappedBy="resolveContractorInvoice", cascade={"persist"})
     *
     * @Serializer\Expose
     */
    protected $resolveResponseRating;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_value", type="decimal", precision=7, scale=2)
     *
     * @Serializer\Expose
     */
    private $paymentValue = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $createdAt;

    public function __construct()
    {
        $this->resolveResponseRating = new ArrayCollection();
    }

    /**
     * Update timestamps before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new DateTime());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime());
        }
    }

    /**
     * Update hash before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function createHash()
    {
        $date = new DateTime();

        if ($this->getHash() == null) {
            $this->setHash(hash("crc32b", $date->getTimestamp()));
        }
    }

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
     * Set hash
     *
     * @param string $hash
     *
     * @return ResolveContractorInvoice
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set paymentValue
     *
     * @param string $paymentValue
     *
     * @return ResolveContractorInvoice
     */
    public function setPaymentValue($paymentValue)
    {
        $this->paymentValue = $paymentValue;

        return $this;
    }

    /**
     * Get paymentValue
     *
     * @return string
     */
    public function getPaymentValue()
    {
        return $this->paymentValue;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ResolveContractorInvoice
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ResolveContractorInvoice
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
     * Set user
     *
     * @param User $user
     *
     * @return ResolveContractorInvoice
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add resolveResponseRating
     *
     * @param ResolveResponseRating $resolveResponseRating
     *
     * @return ResolveContractorInvoice
     */
    public function addResolveResponseRating(ResolveResponseRating $resolveResponseRating)
    {
        $this->resolveResponseRating[] = $resolveResponseRating;

        return $this;
    }

    /**
     * Remove resolveResponseRating
     *
     * @param ResolveResponseRating $resolveResponseRating
     */
    public function removeResolveResponseRating(ResolveResponseRating $resolveResponseRating)
    {
        $this->resolveResponseRating->removeElement($resolveResponseRating);
    }

    /**
     * Get resolveResponseRating
     *
     * @return Collection
     */
    public function getResolveResponseRating()
    {
        return $this->resolveResponseRating;
    }
}
