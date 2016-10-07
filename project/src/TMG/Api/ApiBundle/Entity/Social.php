<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Social
 *
 * @ORM\Table(name="Socials")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\SocialRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Social
{
    public function __construct()
    {
        $this->socialData = new ArrayCollection();
    }

    const NOT_FOUND_MESSAGE = 'Could not find social with property hash of %s';
    
    public static $fillableFields = [
        'active'              => false,
        'url'                 => false,
        'socialData'          => false,
        'clicks'              => false,
        'spent'               => false,
        'impressions'         => false,
        'reach'               => false,
        'multi_property_user' => false,
    ];
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="social")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     **/
    private $property;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

     /**
     * @ORM\OneToMany(targetEntity="SocialData", mappedBy="social")
     **/
    private $socialData;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", nullable=true)
     */
    private $clicks;

    /**
     * @var string
     *
     * @ORM\Column(name="spent", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $spent;

    /**
     * @var integer
     *
     * @ORM\Column(name="impressions", type="integer", nullable=true)
     */
    private $impressions;

    /**
     * @var integer
     *
     * @ORM\Column(name="reach", type="integer", nullable=true)
     */
    private $reach;

    /**
     * @var string
     *
     * @ORM\Column(name="multi_property_user", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $multiPropertyUser;

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
     * @return Social
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Social
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Social
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set socialData
     *
     * @param ArrayCollection $socialData
     *
     * @return Social
     */
    public function setSocialData(ArrayCollection $socialData)
    {
        $this->socialData = $socialData;

        return $this;
    }

    /**
     * Get socialData
     *
     * @return ArrayCollection
     */
    public function getSocialData()
    {
        return $this->socialData;
    }

    /**
     * Add socialData
     *
     * @param SocialData $socialData
     * @return Social
     */
    public function addSocialData(SocialData $socialData)
    {
        $this->socialData[] = $socialData;
        return $this;
    }

    /**
     * Remove socialData
     *
     * @param SocialData $socialData
     * @return Social
     */
    public function removeSocialData(SocialData $socialData)
    {
        $this->socialData->removeElement($socialData);
        return $this;
    }

    /**
     * Has socialData
     *
     * @param SocialData $socialData
     * @return boolean
     */
    public function hasSocialData(SocialData $socialData)
    {
        return $this->socialData->contains($socialData);
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return Social
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;

        return $this;
    }

    /**
     * Get clicks
     *
     * @return integer
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set spent
     *
     * @param string $spent
     * @return Social
     */
    public function setSpent($spent)
    {
        $this->spent = $spent;

        return $this;
    }

    /**
     * Get spent
     *
     * @return string
     */
    public function getSpent()
    {
        return $this->spent;
    }

    /**
     * Set impressions
     *
     * @param integer $impressions
     * @return Social
     */
    public function setImpressions($impressions)
    {
        $this->impressions = $impressions;

        return $this;
    }

    /**
     * Get impressions
     *
     * @return integer
     */
    public function getImpressions()
    {
        return $this->impressions;
    }

    /**
     * Set reach
     *
     * @param integer $reach
     * @return Social
     */
    public function setReach($reach)
    {
        $this->reach = $reach;

        return $this;
    }

    /**
     * Get reach
     *
     * @return integer
     */
    public function getReach()
    {
        return $this->reach;
    }


    /**
     * Set the multi property user name
     *
     * @param string $multiPropertyUser
     *
     * @return Social
     */
    public function setMultiPropertyUser($multiPropertyUser)
    {
        $this->multiPropertyUser = $multiPropertyUser;
        
        return $this;
    }

    /**
     * Get Multi Property User Name
     *
     * @return string
     */
    public function getMultiPropertyUser()
    {
        return $this->multiPropertyUser;
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
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
