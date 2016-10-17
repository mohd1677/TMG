<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\CombinedListingRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CombinedListing extends AbstractEntity
{
    use LatitudeLongitudeTrait;

    /**
     * @var array
     */
    protected static $extraJsonFields = ['distance'];

    public $distance;

    /**
     * 8 character hexadecimal property identifier
     *
     * @ORM\Id
     * @ORM\Column(type="string", nullable=false)
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * A url-friendly string containing the city, state, and property name
     * "Super 8 Motel" in "Orlando", "FL" becomes "super-8-motel-orlando-florida"
     *
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * For online advertisements, this url is for the "Online Rate" link
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $url;

    /**
     * @ORM\OneToOne(targetEntity="Property", cascade={"persist"})
     */
    protected $property;

    /**
     * @ORM\OneToOne(targetEntity="IhgProperty", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="hotel_code")
     */
    protected $ihgProperty;

    /**
     * Custom featured amenities.
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $featuredAmenities;

    /**
     * @ORM\ManyToMany(targetEntity="Amenity")
     */
    protected $amenities;

    /**
     * @ORM\ManyToOne(targetEntity="Address")
     */
    protected $address;

    /**
     * @ORM\OneToOne(targetEntity="Contract")
     *
     * @var Contract
     */
    protected $contract;

    /**
     * @ORM\OneToOne(targetEntity="AxContract")
     *
     * @var AxContract;
     */
    protected $axContract;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isFeatured;

    /**
     * Video, if in "Live"(4) state
     *
     * @ORM\OneToOne(targetEntity="Video", inversedBy="listing")
     */
    protected $video;

    /**
     * @ORM\OneToMany(targetEntity="PropertyPhoto", mappedBy="property")
     * @ORM\JoinTable(
     *     name="property_photos",
     *     joinColumns={@ORM\JoinColumn(name="property_id", referencedColumnName="id")}
     * )
     */
    protected $photos;

    /**
     * @ORM\OneToOne(targetEntity="PropertyAdvertisement")
     */
    protected $advertisement;

    /**
     * The type of coupon this advertisement is for. May be one
     * of the following:
     * <ul>
     *     <li>`dollar` - Coupon is to get a room for a price of {rateValue}</li>
     *     <li>`from-dollar` - Coupon is advertising that rooms start at {rateValue}</li>
     *     <li>`dollar-off` - Coupon provides a fixed dollar amount off of a room price</li>
     *     <li>`percent-off` - Coupon provides a percentage off of a room price</li>
     *     <li>`call-for-rate` - Property must be contacted to recieve a rate</li>
     *     <li>`online-rate` - Visit the propertie's website to view a rate</li>
     * </ul>
     *
     * @ORM\Column(type="string", nullable=false);
     */
    protected $rateType;

    /**
     * How to display the rate to a user
     *
     * @ORM\Column(type="string", nullable=false);
     */
    protected $ratePretty;

    /**
     * The numeric value associated with the rate. Check rateType to know
     * what type of rate this value represents.
     *
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    protected $rateValue;

    /**
     * Phone number to be shown on the advertisement
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phoneNumber;

    /**
     * @ORM\OneToOne(targetEntity="PropertyDescription")
     */
    protected $description;

    /**
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * The timestamp that the rate became active.
     *
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $activeAt;

    /**
     * CombinedListing constructor.
     */
    public function __construct()
    {
        $this->amenities = new ArrayCollection();
        $this->featuredAmenities = [];
        $this->photos = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $v
     *
     * @return CombinedListing
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
     * @return CombinedListing
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $v
     *
     * @return CombinedListing
     */
    public function setSlug($v)
    {
        $this->slug = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $v
     *
     * @return CombinedListing
     */
    public function setUrl($v)
    {
        if ($v) {
            //make sure that url has leading of "http://"
            if (substr($v, 0, 4) == 'http') {
                $this->url = $v;
            } else {
                $this->url = 'http://' . $v;
            }
        } else {
            $this->url = null;
        }

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
     * @return CombinedListing
     */
    public function setProperty(Property $v)
    {
        $this->property = $v;
        $this->id = $v->getId();
        $this->name = $v->getName();
        $this->slug = $v->getSlug();
        $this->address = $v->getAddress();
        $this->latitude = $this->address->getLatitude();
        $this->longitude = $this->address->getLongitude();
        $this->description = $v->getDescription();
        $this->featuredAmenities = $v->getFeaturedAmenities();
        $this->phoneNumber = $v->getPhone();
        $this->url = $v->getUrl();
        $this->ihgProperty = $v->getIhgProperty();

        foreach ($this->amenities as $existingAmenity) {
            $newAmenities = $v->getAmenities();

            if (!$newAmenities->contains($existingAmenity)) {
                $this->amenities->removeElement($existingAmenity);
            }
        }

        foreach ($v->getAmenities() as $amenity) {
            if (!$this->amenities->contains($amenity)) {
                $this->amenities->add($amenity);
            }
        }

        if ($v->getVideo() && $v->getVideo()->getStatus() == 4) {
            $this->setVideo($v->getVideo());
        } else {
            $this->setVideo(null);
        }

        return $this;
    }

    /**
     * @return IhgProperty
     */
    public function getIhgProperty()
    {
        return $this->ihgProperty;
    }

    /**
     * @param IhgProperty $v
     *
     * @return CombinedListing
     */
    public function setIhgProperty(IhgProperty $v)
    {
        $this->ihgProperty = $v;

        return $this;
    }

    /**
     * @return array
     */
    public function getFeaturedAmenities()
    {
        return $this->featuredAmenities;
    }

    /**
     * @param array $v
     *
     * @return CombinedListing
     */
    public function setFeaturedAmenities(array $v)
    {
        $this->featuredAmenities = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAmenities()
    {
        return $this->amenities;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return CombinedListing
     */
    public function setAmenities(ArrayCollection $v)
    {
        $this->amenities = $v;

        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $v
     *
     * @return CombinedListing
     */
    public function setAddress(Address $v)
    {
        $this->address = $v;

        return $this;
    }

    /**
     * @deprecated You should be using AXContract instead
     *
     * @return Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @deprecated You should be using AXContract instead
     *
     * @param Contract $v
     *
     * @return CombinedListing
     */
    public function setContract(Contract $v)
    {
        $this->contract = $v;
        $this->isFeatured = $v->getContractType()->getCode() == 'RFL';

        return $this;
    }

    /**
     * Get AX Contract
     *
     * @return AxContract
     */
    public function getAxContract()
    {
        return $this->axContract;
    }

    /**
     * Set AX Contract
     *
     * @param AxContract $v
     *
     * @return CombinedListing
     */
    public function setAxContract(AxContract $v)
    {
        $this->axContract = $v;
        $this->isFeatured = $v->getItemCode() == 'HTCFPL';

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * @param bool $v
     *
     * @return CombinedListing
     */
    public function setIsFeatured($v)
    {
        $this->isFeatured = $v;
        return $this;
    }

    /**
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param Video $v
     *
     * @return CombinedListing
     */
    public function setVideo($v)
    {
        $this->video = $v;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return CombinedListing
     */
    public function setPhotos(ArrayCollection $v)
    {
        $this->photos = $v;

        return $this;
    }

    /**
     * @param PropertyPhoto $v
     *
     * @return CombinedListing
     */
    public function addPhoto(PropertyPhoto $v)
    {
        $this->photos[] = $v;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdvertisement()
    {
        return $this->advertisement;
    }

    /**
     * @param $v
     *
     * @return CombinedListing
     */
    public function setAdvertisement($v)
    {
        $this->advertisement = $v;
        $this->rateType = $v->getRateType();
        $this->rateValue = $v->getRateValue();
        $this->ratePretty = $v->getRatePretty();
        $this->activeAt =  $v->getStartDate();

        return $this;
    }

    /**
     * @return string
     */
    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * @param string $v
     *
     * @return CombinedListing
     */
    public function setRateType($v)
    {
        $this->rateType = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRatePretty()
    {
        return $this->ratePretty;
    }

    /**
     * @param string $v
     *
     * @return CombinedListing
     */
    public function setRatePretty($v)
    {
        $this->ratePretty = $v;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getRateValue()
    {
        return $this->rateValue;
    }

    /**
     * @param float|int $v
     *
     * @return CombinedListing
     */
    public function setRateValue($v)
    {
        $this->rateValue = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $v
     *
     * @return CombinedListing
     */
    public function setPhoneNumber($v)
    {
        $this->phoneNumber = $v;

        return $this;
    }

    /**
     * @return PropertyDescription
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param PropertyDescription $v
     *
     * @return CombinedListing
     */
    public function setDescription(PropertyDescription $v)
    {
        $this->description = $v;

        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \Datetime $updatedAt
     *
     * @return CombinedListing
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getActiveAt()
    {
        return $this->activeAt;
    }

    /**
     * @param \Datetime $activeAt
     *
     * @return CombinedListing
     */
    public function setActiveAt($activeAt)
    {
        $this->activeAt = $activeAt;

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
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \Datetime
     * @return CombinedListing
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
