<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * **Internal use**
 * @ORM\Entity
 */
class PropertyReport extends AbstractEntity
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
    protected $property;

    /**
     * @ORM\Column(type="string")
     */
    protected $accountNumber;

    /**
     * DateTime the report was added
     *
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    protected $reportDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $rate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $geocode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $registration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
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
     * @return PropertyReport
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $v
     *
     * @return PropertyReport
     */
    public function setProperty($v)
    {
        $this->property = $v;

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
     * @return PropertyReport
     */
    public function setAccountNumber($v)
    {
        $this->accountNumber = $v;

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
     * @param Datetime $v
     *
     * @return PropertyReport
     */
    public function setReportDate(DateTime $v)
    {
        $this->reportDate = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param string $v
     *
     * @return PropertyReport
     */
    public function setRate($v)
    {
        $this->rate = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeocode()
    {
        return $this->geocode;
    }

    /**
     * @param string $v
     *
     * @return PropertyReport
     */
    public function setGeocode($v)
    {
        $this->geocode = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $v
     *
     * @return PropertyReport
     */
    public function setDescription($v)
    {
        $this->description = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $v
     *
     * @return PropertyReport
     */
    public function setPhone($v)
    {
        $this->phone = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $v
     *
     * @return PropertyReport
     */
    public function setEmail($v)
    {
        $this->email = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param string $registration
     *
     * @return PropertyReport
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return PropertyReport
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return PropertyReport
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
