<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="activity_log")
 * @ORM\HasLifecycleCallbacks()
 */
class ActivityLog extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * username or email
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @ORM\ManyToOne(targetEntity="Property")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", nullable=true)
     */
    protected $property;

    /**
     * event or action
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $action;

    /**
     * Timestamp of record creation
     *
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Flag if action was a change
     *
     * @ORM\Column(type="boolean")
     */
    protected $madeChange;

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
     * @return ActivityLog
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $v
     *
     * @return ActivityLog
     */
    public function setUsername($v)
    {
        $this->username = $v;

        return $this;
    }

    /**
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param Property $v
     *
     * @return ActivityLog
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $v
     *
     * @return ActivityLog
     */
    public function setAction($v)
    {
        $this->action = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ActivityLog
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
    }

    /**
     * @return bool
     */
    public function getMadeChange()
    {
        return $this->madeChange;
    }

    /**
     * @param bool $v
     *
     * @return ActivityLog
     */
    public function setMadeChange($v)
    {
        $this->madeChange = $v;

        return $this;
    }
}
