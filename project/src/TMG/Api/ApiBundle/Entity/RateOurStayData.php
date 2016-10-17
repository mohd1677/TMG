<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use TMG\Api\ApiBundle\Entity\Property;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="RateOurStayData")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class RateOurStayData
{
    /**
     * The record ID
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Property ID
     * @var Property
     *
     * @ORM\OneToOne(targetEntity="Property", inversedBy="rateOurStayData", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     **/
    protected $property;

    /**
     * Engage GUID
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Serializer\Expose
     */
    protected $guid;

    /**
     * The subdomain
     * @var string
     *
     * @ORM\OneToMany(targetEntity="RateOurStaySubdomain", mappedBy="rateOurStayData", cascade={"persist"})
     * @Serializer\Expose
     */
    protected $subdomain;

    /**
     * Enabled
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * Timestamp of record creation
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Timestamp of last update
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var array
     */
    protected $subdomainByName;

    public function __construct()
    {
        $this->subdomain = new ArrayCollection();
        $this->subdomainByName = [];
    }

    /**
     * Get ID
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
     *
     * @return RateOurStayData
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
     * Set GUID
     *
     * @param string $guid
     *
     * @return RateOurStayData
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get GUID
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set subdomain
     *
     * @param RateOurStaySubdomain $subdomain
     *
     * @return RateOurStayData
     */
    public function setSubdomain(RateOurStaySubdomain $subdomain)
    {
        $this->subdomain[] = $subdomain;

        return $this;
    }

    /**
     * Get subdomain
     *
     * @return array
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    public function getSubDomainByName()
    {
        /** @var RateOurStaySubdomain $subdomain */
        foreach ($this->subdomain as $subdomain) {
            $this->subdomainByName[$subdomain->getSubdomain()] = $subdomain;
        }

        return $this->subdomainByName;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return RateOurStayData
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RateOurStayData
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
     * @return RateOurStayData
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
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
        $this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
}
