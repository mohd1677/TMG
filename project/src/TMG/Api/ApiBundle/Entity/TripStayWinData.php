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
 * @ORM\Table(name="TripStayWinData")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class TripStayWinData
{
    const NOT_FOUND_MESSAGE = "Could not find TripStayWinData for %s";

    /**
     * Fields that are required when creating a new Property.
     * @var array
     */
    public static $requiredPostFields = [
        "enabled" => true,
        "logoPath" => false,
        "twitterName" => false,
        "sweepstakesDesktop" => false,
        "sweepstakesMobile" => false,
        "facebookPageLink" => false,
        "twitterPageLink" => false,
        "googlePageLink" => false,
        "pinterestPageLink" => false
    ];

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
     * The subdomain
     * @var string
     *
     * @ORM\OneToMany(targetEntity="RateOurStaySubdomain", mappedBy="tripStayWinData", cascade={"persist"})
     *
     * @Serializer\Expose
     */
    protected $subdomain;

    /**
     * Enabled
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Serializer\Expose
     */
    protected $enabled;

    /**
     * Twitter widget embed code
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $twitterName;

    /**
     * Path to the logo image
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $logoPath;

    /**
     * Desktop variant of the sweepstakes URL
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $sweepstakesDesktop;

    /**
     * Mobile variant of the sweepstakes URL
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $sweepstakesMobile;

    /**
     * Facebook page link
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $facebookPageLink;

    /**
     * Twitter page link.
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $twitterPageLink;

    /**
     * Google page link.
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $googlePageLink;
    
    /**
     * Pinterest page link.
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $pinterestPageLink;

    /**
     * Timestamp of record creation
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    protected $subdomainByName;

    /**
     * Timestamp of last update
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->subdomain = new ArrayCollection();
        $this->subdomainByName = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @return array
     */
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
     * Get twitterWidget
     *
     * @return string
     */
    public function getTwitterName()
    {
        return $this->twitterName;
    }

    /**
     * Set twitterWidget
     *
     * @param string $twitterName
     *
     * @return TripStayWinData
     */
    public function setTwitterName($twitterName)
    {
        $this->twitterName = trim($twitterName);

        return $this;
    }

    /**
     * Get logoPath
     *
     * @return string
     */
    public function getLogoPath()
    {
        return $this->logoPath;
    }

    /**
     * Set logoPath
     *
     * @param string $logoPath
     *
     * @return TripStayWinData
     */
    public function setLogoPath($logoPath)
    {
        $this->logoPath = $logoPath;

        return $this;
    }

    /**
     * Get sweepstakesDesktop
     *
     * @return string
     */
    public function getSweepstakesDesktop()
    {
        return $this->sweepstakesDesktop;
    }

    /**
     * Set sweepstakesDesktop
     *
     * @param string $sweepstakesDesktop
     *
     * @return TripStayWinData
     */
    public function setSweepstakesDesktop($sweepstakesDesktop)
    {
        $this->sweepstakesDesktop = $sweepstakesDesktop;

        return $this;
    }

    /**
     * Get sweepstakesMobile
     *
     * @return string
     */
    public function getSweepstakesMobile()
    {
        return $this->sweepstakesMobile;
    }

    /**
     * Set sweepstakesMobile
     *
     * @param string $sweepstakesMobile
     *
     * @return TripStayWinData
     */
    public function setSweepstakesMobile($sweepstakesMobile)
    {
        $this->sweepstakesMobile = $sweepstakesMobile;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookPageLink()
    {
        return $this->facebookPageLink;
    }

    /**
     * @param $facebookPageLink
     * @return $this
     */
    public function setFacebookPageLink($facebookPageLink)
    {
        $this->facebookPageLink = $facebookPageLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterPageLink()
    {
        return $this->twitterPageLink;
    }

    /**
     * @param $twitterPageLink
     * @return $this
     */
    public function setTwitterPageLink($twitterPageLink)
    {
        $this->twitterPageLink = $twitterPageLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getGooglePageLink()
    {
        return $this->googlePageLink;
    }

    /**
     * @param $googlePageLink
     * @return $this
     */
    public function setGooglePageLink($googlePageLink)
    {
        $this->googlePageLink = $googlePageLink;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getPinterestPageLink()
    {
        return $this->pinterestPageLink;
    }

    /**
     * @param $pinterestPageLink
     * @return $this
     */
    public function setPinterestPageLink($pinterestPageLink)
    {
        $this->pinterestPageLink = $pinterestPageLink;

        return $this;
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
