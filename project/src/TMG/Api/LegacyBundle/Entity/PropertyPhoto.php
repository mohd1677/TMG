<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class PropertyPhoto extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Is this photo the primary photo to be shown for a property search result
     * @ORM\Column(type="boolean")
     * @Serializer\Expose
     */
    protected $isDisplayImg;

    /**
     * Is this photo the banner image for the TMG dashboard
     * @ORM\Column(type="boolean");
     * @Serializer\Expose
     */
    protected $isBannerImg;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="photos")
     * @Assert\Valid
     */
    protected $property;

    /**
     * URL of the original image. Dimensions are as uploaded, but at
     * least as wide & high as large (420x236)
     * @ORM\Column(type="string", length=512);
     * @Serializer\Expose
     */
    protected $urlOriginal;

    /**
     * URL of the extra large (1280x720) image. May be null
     * @ORM\Column(type="string", length=512, nullable=true)
     * @Serializer\Expose
     */
    protected $urlExtraLarge;

    /**
     * URL of the large (420x236) image.
     * @ORM\Column(type="string", length=512)
     * @Serializer\Expose
     */
    protected $urlLarge;

    /**
     * URL of the medium (295x166) image.
     * @ORM\Column(type="string", length=512)
     * @Serializer\Expose
     */
    protected $urlMedium;

    /**
     * URL of the small (177x100) image.
     * @ORM\Column(type="string", length=512)
     * @Serializer\Expose
     */
    protected $urlSmall;

    /**
     * URL of the extra small (79x44) image.
     * @ORM\Column(type="string", length=512)
     * @Serializer\Expose
     */
    protected $urlExtraSmall;

    /**
     * URL of the large featured(210x158, 4:3) image.
     * @ORM\Column(type="string", length=512)
     * @Serializer\Expose
     */
    protected $urlLargeFeatured;

    /**
     * URL of the small featured (172x129, 4:3) image.
     * @ORM\Column(type="string", length=512)
     * @Serializer\Expose
     */
    protected $urlSmallFeatured;

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
     * @return PropertyPhoto
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDisplayImg()
    {
        return $this->isDisplayImg;
    }

    /**
     * @param bool $v
     *
     * @return PropertyPhoto
     */
    public function setIsDisplayImg($v)
    {
        $this->isDisplayImg = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsBannerImg()
    {
        return $this->isBannerImg;
    }

    /**
     * @param bool $v
     *
     * @return PropertyPhoto
     */
    public function setIsBannerImg($v)
    {
        $this->isBannerImg = $v;

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
     * @return PropertyPhoto
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlOriginal()
    {
        return $this->urlOriginal;
    }

    /**
     * @param string $v
     *
     * @return PropertyPhoto
     */
    public function setUrlOriginal($v)
    {
        $this->urlOriginal = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlExtraLarge()
    {
        return $this->urlExtraLarge;
    }

    /**
     * @param string $v
     *
     * @return PropertyPhoto
     */
    public function setUrlExtraLarge($v)
    {
        $this->urlExtraLarge = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlLarge()
    {
        return $this->urlLarge;
    }

    /**
     * @param string $v
     *
     * @return PropertyPhoto
     */
    public function setUrlLarge($v)
    {
        $this->urlLarge = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlMedium()
    {
        return $this->urlMedium;
    }

    /**
     * @param string $v
     *
     * @return PropertyPhoto
     */
    public function setUrlMedium($v)
    {
        $this->urlMedium = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlSmall()
    {
        return $this->urlSmall;
    }

    /**
     * @param string $v
     *
     * @return PropertyPhoto
     */
    public function setUrlSmall($v)
    {
        $this->urlSmall = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlExtraSmall()
    {
        return $this->urlExtraSmall;
    }

    /**
     * @param string $v
     * @return PropertyPhoto
     */
    public function setUrlExtraSmall($v)
    {
        $this->urlExtraSmall = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlLargeFeatured()
    {
        return $this->urlLargeFeatured;
    }

    /**
     * @param string $v
     *
     * @return PropertyPhoto
     */
    public function setUrlLargeFeatured($v)
    {
        $this->urlLargeFeatured = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlSmallFeatured()
    {
        return $this->urlSmallFeatured;
    }

    /**
     * @param string $v
     *
     * @return PropertyPhoto
     */
    public function setUrlSmallFeatured($v)
    {
        $this->urlSmallFeatured = $v;

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
     * @return PropertyPhoto
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
     * @return PropertyPhoto
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
