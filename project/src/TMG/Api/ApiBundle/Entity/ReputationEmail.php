<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReputationEmail
 *
 * @ORM\Table(name="ReputationEmails")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationEmailRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ReputationEmail
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
     * @ORM\ManyToOne(targetEntity="Reputation", inversedBy="emails")
     * @ORM\JoinColumn(name="reputation_id", referencedColumnName="id")
     **/
    private $reputation;

    /**
     * @var integer
     *
     * @ORM\Column(name="yrmo", type="integer", length=4)
     */
    private $yrmo;

    /**
     * @var integer
     *
     * @ORM\Column(name="sent", type="integer", nullable=true)
     */
    private $sent;

    /**
     * @var integer
     *
     * @ORM\Column(name="opened", type="integer", nullable=true)
     */
    private $opened;

    /**
     * @var integer
     *
     * @ORM\Column(name="yes", type="integer", nullable=true)
     */
    private $yes;

    /**
     * @var integer
     *
     * @ORM\Column(name="no", type="integer", nullable=true)
     */
    private $no;

    /**
     * @var integer
     *
     * @ORM\Column(name="redirects", type="integer", nullable=true)
     */
    private $redirects;

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
     * @var \DateTime
     *
     * @ORM\Column(name="latest_date_included", type="datetime")
     */
    private $latestDateIncluded;

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
     * Set reputation
     *
     * @param Reputation $reputation
     *
     * @return ReputationEmail
     */
    public function setReputation(Reputation $reputation)
    {
        $this->reputation = $reputation;

        return $this;
    }

    /**
     * Get reputation
     *
     * @return Reputation
     */
    public function getReputation()
    {
        return $this->reputation;
    }

    /**
     * Set yrmo
     *
     * @param integer|DateTime $yrmo
     *
     * @return ReputationEmail
     */
    public function setYrmo($yrmo)
    {
        $this->yrmo = $this->formatYRMO($yrmo);

        return $this;
    }

    /**
     * Get yrmo
     *
     * @return integer
     */
    public function getYrmo()
    {
        return $this->yrmo;
    }

    /**
     * Set sent
     *
     * @param integer $sent
     *
     * @return ReputationEmail
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return integer
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Set opened
     *
     * @param integer $opened
     *
     * @return ReputationEmail
     */
    public function setOpened($opened)
    {
        $this->opened = $opened;

        return $this;
    }

    /**
     * Get opened
     *
     * @return integer
     */
    public function getOpened()
    {
        return $this->opened;
    }

    /**
     * Set yes
     *
     * @param integer $yes
     *
     * @return ReputationEmail
     */
    public function setYes($yes)
    {
        $this->yes = $yes;

        return $this;
    }

    /**
     * Get yes
     *
     * @return integer
     */
    public function getYes()
    {
        return $this->yes;
    }

    /**
     * Set no
     *
     * @param integer $no
     *
     * @return ReputationEmail
     */
    public function setNo($no)
    {
        $this->no = $no;

        return $this;
    }

    /**
     * Get no
     *
     * @return integer
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * Set redirects
     *
     * @param integer $redirects
     *
     * @return ReputationEmail
     */
    public function setRedirects($redirects)
    {
        $this->redirects = $redirects;

        return $this;
    }

    /**
     * Get redirects
     *
     * @return integer
     */
    public function getRedirects()
    {
        return $this->redirects;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ReputationEmail
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
     *
     * @return ReputationEmail
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
     * Set latestDateIncluded
     *
     * @return ReputationEmail
     */
    public function setLatestDateIncluded($latestDateIncluded)
    {
        $this->latestDateIncluded = $latestDateIncluded;

        return $this;
    }

    /**
     * Get latestDateIncluded
     *
     * @return \DateTime
     */
    public function getLatestDateIncluded()
    {
        return $this->latestDateIncluded;
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

    /**
     * Format YRMO
     *
     * @return integer
     */
    public function formatYRMO($date)
    {
        if ($date instanceof \DateTime) {
            $date = $date->format('ym');
            return (int) $date;
        } else {
            $date = new \DateTime($date);
            $date = $date->format('ym');
            return (int) $date;
        }
    }
}
