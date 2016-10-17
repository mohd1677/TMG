<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ImporterReport extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $log;

    /**
     * Importer runtime in seconds
     *
     * @ORM\Column(type="integer")
     */
    protected $duration;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isLatest;

    /**
     * DateTime the report was added
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date
     */
    protected $reportDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="completed_at", type="datetime", nullable=true)
     */
    private $completedAt;

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
     * @return ImporterReport
     */
    public function setId($v)
    {
        $this->id = $v;

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
     * @return ImporterReport
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $v
     *
     * @return ImporterReport
     */
    public function setStatus($v)
    {
        $this->status = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param string $v
     *
     * @return ImporterReport
     */
    public function setLog($v)
    {
        $this->log = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $v
     *
     * @return ImporterReport
     */
    public function setDuration($v)
    {
        $this->duration = $v;

        return $this;
    }

    /**
     * @return bool
     *
     * @deprecated Use isLatest() instead.
     */
    public function getIsLatest()
    {
        return $this->isLatest;
    }

    /**
     * @param bool $v
     *
     * @return ImporterReport
     *
     * @deprecated Use setLatest() instead.
     */
    public function setIsLatest($v)
    {
        $this->isLatest = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLatest()
    {
        return $this->isLatest;
    }

    /**
     * @param bool $v
     *
     * @return ImporterReport
     */
    public function setLatest($v)
    {
        $this->isLatest = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReportDate()
    {
        return $this->reportDate;
    }

    /**
     * @param \DateTime $v
     *
     * @return ImporterReport
     */
    public function setReportDate(\DateTime $v)
    {
        $this->reportDate = $v;

        return $this;
    }

    /**
     * Get completedAt
     *
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * Set completedAt
     *
     * @param \DateTime $completedAt
     *
     * @return ImporterReport
     */
    public function setCompletedAt(\DateTime $completedAt)
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ImporterReport
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
     * @return ImporterReport
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
