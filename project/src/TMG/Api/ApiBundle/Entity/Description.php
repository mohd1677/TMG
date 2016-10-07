<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Description
 *
 * @ORM\Table(name="Descriptions")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\DescriptionRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Description
{
    const NOT_FOUND_MESSAGE = "Could not find video description for video with id of %s";

    public static $fillable = [
        "bannerImage" => false,
        "displayImage" => false,
        "restrictions" => false,
        "directions" => false,
        "briefDescription" => false,
        "description" => true,
        "url" => false
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="description")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     **/
    private $property;

    /**
     * @ORM\OneToOne(targetEntity="Video", mappedBy="description")
     **/
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="brief_description", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $briefDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="directions", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $directions;

    /**
     * @var string
     *
     * @ORM\Column(name="restrictions", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $restrictions;

    /**
     * @var string
     *
     * @ORM\Column(name="display_image", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $displayImage;

    /**
     * @var string
     *
     * @ORM\Column(name="banner_image", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $bannerImage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose
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
     * @return Description
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set video
     *
     * @return Description
     */
    public function setVideo(Video $video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     */
    public function getVideo()
    {
        return $this->video;
    }


    /**
     * Set url
     *
     * @param string $url
     * @return Description
     */
    public function setUrl($url)
    {
        if ($url) {
            //make sure that url has leading of "http://"
            if (substr($url, 0, 4) == 'http') {
                $this->url = $url;
                return $this;
            } else {
                $this->url = 'http://' . $url;
                return $this;
            }
        } else {
            $this->url = null;
            return $this;
        }
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
     * Set description
     *
     * @param string $description
     * @return Description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set briefDescription
     *
     * @param string $briefDescription
     * @return Description
     */
    public function setBriefDescription($briefDescription)
    {
        $this->briefDescription = $briefDescription;

        return $this;
    }

    /**
     * Get briefDescription
     *
     * @return string
     */
    public function getBriefDescription()
    {
        return $this->briefDescription;
    }

    /**
     * Set directions
     *
     * @param string $directions
     * @return Description
     */
    public function setDirections($directions)
    {
        $this->directions = $directions;

        return $this;
    }

    /**
     * Get directions
     *
     * @return string
     */
    public function getDirections()
    {
        return $this->directions;
    }

    /**
     * Set restrictions
     *
     * @param string $restrictions
     * @return Description
     */
    public function setRestrictions($restrictions)
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    /**
     * Get restrictions
     *
     * @return string
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * Set displayImage
     *
     * @param string $displayImage
     * @return Description
     */
    public function setDisplayImage($displayImage)
    {
        $this->displayImage = $displayImage;

        return $this;
    }

    /**
     * Get displayImage
     *
     * @return string
     */
    public function getDisplayImage()
    {
        return $this->displayImage;
    }

    /**
     * Set bannerImage
     *
     * @param string $bannerImage
     * @return Description
     */
    public function setBannerImage($bannerImage)
    {
        $this->bannerImage = $bannerImage;

        return $this;
    }

    /**
     * Get bannerImage
     *
     * @return string
     */
    public function getBannerImage()
    {
        return $this->bannerImage;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Description
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
     * @return Description
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
