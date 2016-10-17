<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\UserBundle\Entity\User;

/**
 * Activity
 *
 * @ORM\Table(name="Activities")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ActivityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Activity
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
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", nullable=true)
     **/
    private $property;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="active_user", referencedColumnName="id", nullable=false)
     **/
    private $activeUser;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @var boolean
     *
     * @ORM\Column(name="made_change", type="boolean")
     */
    private $madeChange;

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
     * Set property
     *
     * @param Property $property
     * @return Activity
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set activeUser
     *
     * @param User $activeUser
     * @return Activity
     */
    public function setActiveUser(User $activeUser)
    {
        $this->activeUser = $activeUser;

        return $this;
    }

    /**
     * Get activeUser
     *
     * @return string
     */
    public function getActiveUser()
    {
        return $this->activeUser;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return Activity
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set madeChange
     *
     * @param boolean $madeChange
     * @return Activity
     */
    public function setMadeChange($madeChange)
    {
        $this->madeChange = $madeChange;

        return $this;
    }

    /**
     * Get madeChange
     *
     * @return boolean
     */
    public function getMadeChange()
    {
        return $this->madeChange;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Activity
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
     * @return Activity
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
