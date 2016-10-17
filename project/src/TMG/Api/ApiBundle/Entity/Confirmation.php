<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use TMG\Api\UserBundle\Entity\User;

/**
 * Confirmation
 *
 * @ORM\Table(name="Confirmations")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ConfirmationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Confirmation
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
     * @ORM\ManyToOne(targetEntity="Contract", inversedBy="confirmations")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     **/
    private $contract;

    /**
     * @var integer
     *
     * @ORM\Column(name="confirmed_issue", type="integer")
     */
    private $confirmedIssue;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="confirmed_by", referencedColumnName="id", nullable=true)
     **/
    private $confirmedBy;

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
     * Set contract
     *
     * @param Contract $contract
     * @return Confirmation
     */
    public function setContract(Contract $contract)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract
     *
     * @return string
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set confirmedIssue
     *
     * @param integer $confirmedIssue
     * @return Confirmation
     */
    public function setConfirmedIssue($confirmedIssue)
    {
        $this->confirmedIssue = $confirmedIssue;

        return $this;
    }

    /**
     * Get confirmedIssue
     *
     * @return integer
     */
    public function getConfirmedIssue()
    {
        return $this->confirmedIssue;
    }

    /**
     * Set confirmedBy
     *
     * @param User $confirmedBy
     * @return Confirmation
     */
    public function setConfirmedBy(User $confirmedBy)
    {
        $this->confirmedBy = $confirmedBy;

        return $this;
    }

    /**
     * Get confirmedBy
     *
     * @return string
     */
    public function getConfirmedBy()
    {
        return $this->confirmedBy;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Confirmation
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
     * @return Confirmation
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
