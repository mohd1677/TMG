<?php

namespace TMG\Api\ApiBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * Property
 *
 * @ORM\Table(name="Properties")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\PropertyRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Property
{
    const NOT_FOUND_MESSAGE = "Could not find property with hash code of %s";

    /**
     * Fields that are required when creating a new Property.
     * @var array
     */
    public static $requiredPostFields = [
        "name" => true,
        "contact_name" => true,
        "email" => true,
        "phone" => true,
        "ax_number" => true,
        "fax" => false,
        "account_phone" => false,
        "send_fax" => true,
        "send_email" => true,
        "slug" => false,
        "rate_lock" => true,
        "force_live" => true,
        "brand_url" => false,
        "featured" => false,
        "sms_enabled" => true,
        "hash" => false,
        "address" => false,
        "billing_address" => false,
        "amenities" => true,
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
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "hash",
     *     "review_detail",
     *     "resolve_property",
     * })
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="ax_number", type="string", length=40, unique=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "feedback",
     *     "review_detail",
     *     "resolve_property",
     * })
     */
    private $axNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="property_number", type="string", length=40, nullable=true)
     *
     * @Serializer\Expose
     */
    private $propertyNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     *     "resolve_property",
     * })
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_name", type="string", length=255, nullable=true)
     */
    private $contactName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="account_phone", type="string", length=255, nullable=true)
     */
    private $accountPhone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="send_fax", type="boolean", options={"default" = 0})
     */
    private $sendFax;

    /**
     * @var boolean
     *
     * @ORM\Column(name="send_email", type="boolean", options={"default" = 0})
     */
    private $sendEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var array
     *
     * @ORM\Column(name="featured_amenities", type="array", nullable=true)
     */
    private $featuredAmenities;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rate_lock", type="boolean", options={"default" = 0})
     */
    private $rateLock;

    /**
     * @var boolean
     *
     * @ORM\Column(name="force_live", type="boolean")
     */
    private $forceLive = false;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $address;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist"})
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $billingAddress;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Amenities")
     * @ORM\JoinTable(name="property_amenities",
     *      joinColumns={@ORM\joinColumn(name="property_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="amenity_id", referencedColumnName="id")}
     * )
     *
     * @Serializer\Expose
     */
    private $amenities;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TMG\Api\UserBundle\Entity\User", mappedBy="properties")
     */
    private $users;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="property")
     **/
    private $contracts;

    /**
     * @var Description
     *
     * @ORM\OneToOne(targetEntity="Description", mappedBy="property")
     **/
    private $description;

    /**
     * @var Video
     *
     * @ORM\OneToOne(targetEntity="Video", mappedBy="property")
     **/
    private $video;

    /**
     * @var IHGProperty
     *
     * @ORM\OneToOne(targetEntity="IHGProperty", mappedBy="property")
     **/
    private $ihgProperty;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Rate", mappedBy="property")
     **/
    private $rates;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="property")
     * @Serializer\Expose
     **/
    private $photos;

    /**
     * @var Social
     *
     * @ORM\OneToOne(targetEntity="Social", mappedBy="property")
     **/
    private $social;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TollFree", mappedBy="property")
     **/
    private $tollFrees;

    /**
     * @var Brand
     *
     * @ORM\ManyToOne(targetEntity="Brand", cascade={"persist"})
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="brand_url", type="string", length=255, nullable=true)
     */
    private $brandUrl;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TMG\Api\UserBundle\Entity\User", mappedBy="favorites")
     */
    private $favorites;

    /**
     * @var boolean
     *
     * @ORM\Column(name="featured", type="boolean", nullable=true)
     */
    private $featured;

    /**
     * @var Website
     *
     * @ORM\OneToOne(targetEntity="Website", mappedBy="property")
     **/
    private $website;

    /**
     * @var Reputation
     *
     * @ORM\OneToOne(targetEntity="Reputation", mappedBy="property")
     **/
    private $reputation;

    /**
     * @var RateOurStayData
     *
     * @ORM\OneToOne(targetEntity="RateOurStayData", mappedBy="property", cascade={"persist"})
     * @ORM\JoinColumn(name="rateOurStayData_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $rateOurStayData;

    /**
     * @var TripStayWinData
     *
     * @ORM\OneToOne(targetEntity="TripStayWinData", cascade={"persist"})
     * @ORM\JoinColumn(name="tripStayWinData_id", referencedColumnName="id")
     *
     * @Serializer\Expose
     */
    private $tripStayWinData;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sms_enabled", type="boolean")
     */
    private $smsEnabled = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LocalEvent", mappedBy="property", cascade={"persist"})
     *
     * @Serializer\Expose
     */
    private $localEvents;

    /**
     * @var ResolveSetting
     *
     * @ORM\OneToOne(targetEntity="ResolveSetting", mappedBy="property", cascade={"persist"})
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     *     "resolve_property",
     * })
     */
    private $resolveSetting;

    public function __construct()
    {
        $this->featuredAmenities = [];
        $this->users = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->contracts = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->tollFrees = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->localEvents = new ArrayCollection();
    }

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
     * Set hash
     *
     * @param string $hash
     * @return Property
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set address
     *
     * @param Address $address
     *
     * @return Property
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set billingAddress
     *
     * @param Address $billingAddress
     * @return Property
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set axNumber
     *
     * @param string $axNumber
     * @return Property
     */
    public function setAxNumber($axNumber)
    {
        $this->axNumber = $axNumber;

        return $this;
    }

    /**
     * Get axNumber
     *
     * @return string
     */
    public function getAxNumber()
    {
        return $this->axNumber;
    }

    /**
     * Set propertyNumber
     *
     * @param string $propertyNumber
     * @return Property
     */
    public function setPropertyNumber($propertyNumber)
    {
        $this->propertyNumber = $propertyNumber;

        return $this;
    }

    /**
     * Get propertyNumber
     *
     * @return string
     */
    public function getPropertyNumber()
    {
        return $this->propertyNumber;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Property
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setSlug($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     * @return Property
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Property
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Property
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Property
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set accountPhone
     *
     * @param string $accountPhone
     * @return Property
     */
    public function setAccountPhone($accountPhone)
    {
        $this->accountPhone = $accountPhone;

        return $this;
    }

    /**
     * Get accountPhone
     *
     * @return string
     */
    public function getAccountPhone()
    {
        return $this->accountPhone;
    }

    /**
     * Set sendFax
     *
     * @param boolean $sendFax
     * @return Property
     */
    public function setSendFax($sendFax)
    {
        $this->sendFax = $sendFax;

        return $this;
    }

    /**
     * Get sendFax
     *
     * @return boolean
     *
     * @deprecated Use hasSendFax() instead
     */
    public function getSendFax()
    {
        return $this->sendFax;
    }

    /**
     * Get sendFax
     *
     * @return boolean
     */
    public function hasSendFax()
    {
        return $this->sendFax;
    }

    /**
     * Set sendEmail
     *
     * @param boolean $sendEmail
     * @return Property
     */
    public function setSendEmail($sendEmail)
    {
        $this->sendEmail = $sendEmail;

        return $this;
    }

    /**
     * Get sendEmail
     *
     * @return boolean
     *
     * @deprecated Use hasSendEmail() instead
     */
    public function getSendEmail()
    {
        return $this->sendEmail;
    }

    /**
     * Get sendEmail
     *
     * @return boolean
     */
    public function hasSendEmail()
    {
        return $this->sendEmail;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Property
     */
    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set featuredAmenities
     *
     * @param array $featuredAmenities
     * @return Property
     */
    public function setFeaturedAmenities(array $featuredAmenities)
    {
        $this->featuredAmenities = $featuredAmenities;

        return $this;
    }

    /**
     * Get featuredAmenities
     *
     * @return array
     */
    public function getFeaturedAmenities()
    {
        return $this->featuredAmenities;
    }

    /**
     * Set amenities
     *
     * @param ArrayCollection $amenities
     * @return Property
     */
    public function setAmenities(ArrayCollection $amenities)
    {
        $this->amenities = $amenities;

        return $this;
    }

    /**
     * Get amenities
     *
     * @return ArrayCollection
     */
    public function getAmenities()
    {
        return $this->amenities;
    }

    /**
     * Add Amenity
     *
     * @param Amenities
     * @return Property
     */
    public function addAmenity(Amenities $amenity)
    {
        $this->amenities[] = $amenity;

        return $this;
    }

    /**
     * Remove Amenity
     *
     * @param Amenities
     * @return Property
     */
    public function removeAmenity(Amenities $amenity)
    {
        $this->amenities->removeElement($amenity);

        return $this;
    }

    /**
     * Has Amenity
     *
     * @param Amenities
     * @return boolean
     */
    public function hasAmenity(Amenities $amenity)
    {
        return $this->amenities->contains($amenity);
    }

    /**
     * Set users
     *
     * @param string $users
     * @return Property
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return string
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set rateLock
     *
     * @param boolean $rateLock
     *
     * @return Property
     */
    public function setRateLock($rateLock)
    {
        $this->rateLock = $rateLock;

        return $this;
    }

    /**
     * Get rateLock
     *
     * @return boolean
     *
     * @deprecated Use hasRateLock() instead
     */
    public function getRateLock()
    {
        return $this->rateLock;
    }

    /**
     * Get rateLock
     *
     * @return boolean
     */
    public function hasRateLock()
    {
        return $this->rateLock;
    }


    /**
     * Set forceLive
     *
     * @param boolean $forceLive
     * @return Property
     */
    public function setForceLive($forceLive)
    {
        $this->forceLive = $forceLive;

        return $this;
    }

    /**
     * Get forceLive
     *
     * @return boolean
     *
     * @deprecated Use isForcedLive() instead
     */
    public function getForceLive()
    {
        return $this->forceLive;
    }

    /**
     * Get forceLive
     *
     * @return boolean
     */
    public function isForcedLive()
    {
        return $this->forceLive;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     * @return Property
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     * @return Property
     */
    public function setUpdatedAt($updatedAt)
    {
        /** @var DateTime updatedAt */
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set contracts
     *
     * @param ArrayCollection $contracts
     * @return Property
     */
    public function setContracts(ArrayCollection $contracts)
    {
        $this->contracts = $contracts;

        return $this;
    }

    /**
     * Get contracts
     *
     * @return string
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Add Contract
     *
     * @param Contract
     * @return Property
     */
    public function addContract(Contract $v)
    {
        $this->contracts[] = $v;

        return $this;
    }

    /**
     * Remove Contract
     *
     * @param Contract
     * @return Property
     */
    public function removeContract(Contract $v)
    {
        $this->contracts->removeElement($v);

        return $this;
    }

    /**
     * Has Contract
     *
     * @param Contract
     * @return boolean
     */
    public function hasContract(Contract $v)
    {
        return $this->contracts->contains($v);
    }

    /**
     * Set description
     *
     * @param Description $description
     * @return Property
     */
    public function setDescription(Description $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set video
     *
     * @param Video $video
     * @return Property
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
     * Set ihgProperty
     *
     * @param IHGProperty $ihgProperty
     * @return Property
     */
    public function setIhgProperty(IHGProperty $ihgProperty)
    {
        $this->ihgProperty = $ihgProperty;

        return $this;
    }

    /**
     * Get ihgProperty
     *
     * @return IHGProperty
     */
    public function getIhgProperty()
    {
        return $this->ihgProperty;
    }

    /**
     * Set rates
     *
     * @param ArrayCollection $rates
     * @return Property
     */
    public function setRates(ArrayCollection $rates)
    {
        $this->rates = $rates;

        return $this;
    }

    /**
     * Get rates
     *
     * @return string
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Add rate
     *
     * @param Rate $rate
     * @return Property
     */
    public function addRate(Rate $rate)
    {
        $this->rates[] = $rate;

        return $this;
    }

    /**
     * Remove Rate
     *
     * @param Rate $rate
     * @return Property
     */
    public function removeRate(Rate $rate)
    {
        $this->rates->removeElement($rate);

        return $this;
    }

    /**
     * Has Rate
     *
     * @param Rate $rate
     * @return boolean
     */
    public function hasRate(Rate $rate)
    {
        return $this->rates->contains($rate);
    }

    /**
     * Set photos
     *
     * @param ArrayCollection $photos
     * @return Property
     */
    public function setPhotos(ArrayCollection $photos)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * Get photos
     *
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Add photo
     *
     * @param Photo $photo
     * @return Property
     */
    public function addPhoto(Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param Photo $photo
     * @return Property
     */
    public function removePhoto(Photo $photo)
    {
        $this->photos->removeElement($photo);

        return $this;
    }

    /**
     * Has photo
     *
     * @param Photo $photo
     * @return boolean
     */
    public function hasPhoto(Photo $photo)
    {
        return $this->photos->contains($photo);
    }

    /**
     * Set social
     *
     * @param Social $social
     * @return Property
     */
    public function setSocial(Social $social)
    {
        $this->social = $social;

        return $this;
    }

    /**
     * Get social
     *
     */
    public function getSocial()
    {
        return $this->social;
    }

    /**
     * Set tollFrees
     *
     * @param ArrayCollection $tollFrees
     * @return Property
     */
    public function setTollFrees(ArrayCollection $tollFrees)
    {
        $this->tollFrees = $tollFrees;

        return $this;
    }

    /**
     * Get tollFrees
     *
     * @return ArrayCollection
     */
    public function getTollFrees()
    {
        return $this->tollFrees;
    }

    /**
     * Add tollFree
     *
     * @param TollFree $tollFree
     * @return Property
     */
    public function addTollFree(TollFree $tollFree)
    {
        $this->tollFrees[] = $tollFree;

        return $this;
    }

    /**
     * Remove tollFree
     *
     * @param TollFree $tollFree
     * @return Property
     */
    public function removeTollFree(TollFree $tollFree)
    {
        $this->tollFrees->removeElement($tollFree);

        return $this;
    }

    /**
     * Has tollFree
     *
     * @param TollFree $tollFree
     * @return boolean
     */
    public function hasTollFree(TollFree $tollFree)
    {
        return $this->tollFrees->contains($tollFree);
    }

    /**
     * Set brand
     *
     * @param Brand $brand
     * @return Property
     */
    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set brandUrl
     *
     * @param string $brandUrl
     * @return Property
     */
    public function setBrandUrl($brandUrl)
    {
        $this->brandUrl = $brandUrl;

        return $this;
    }

    /**
     * Get brandUrl
     *
     * @return string
     */
    public function getBrandUrl()
    {
        return $this->brandUrl;
    }

    /**
     * Set favorites
     *
     * @param ArrayCollection $favorites
     * @return Property
     */
    public function setFavorites(ArrayCollection $favorites)
    {
        $this->favorites = $favorites;

        return $this;
    }

    /**
     * Get favorites
     *
     * @return ArrayCollection
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * Add favorite
     *
     * @param $favorite
     * @return Property
     */
    public function addFavorite($favorite)
    {
        $this->favorites[] = $favorite;

        return $this;
    }

    /**
     * Remove favorite
     *
     * @param $favorite
     * @return Property
     */
    public function removeFavorite($favorite)
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    /**
     * Has favorite
     *
     * @param $favorite
     * @return bool
     */
    public function hasFavorite($favorite)
    {
        return $this->favorites->contains($favorite);
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     * @return Property
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean
     *
     * @deprecated Use isFeatured() instead
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * Set website
     *
     * @param Website $website
     * @return Property
     */
    public function setWebsite(Website $website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set reputation
     *
     * @param Reputation $reputation
     * @return Property
     */
    public function setReputation(Reputation $reputation)
    {
        $this->reputation = $reputation;

        return $this;
    }

    /**
     * Get reputation
     *
     * @return Reputation
     */
    public function getReputation()
    {
        return $this->reputation;
    }

    /**
     * Set RateOurStayData
     *
     * @param RateOurStayData $rateOurStayData
     *
     * @return Property
     */
    public function setRateOurStayData(RateOurStayData $rateOurStayData)
    {
        $this->rateOurStayData = $rateOurStayData;

        return $this;
    }

    /**
     * Get RateOurStayData
     *
     * @return RateOurStayData
     */
    public function getRateOurStayData()
    {
        return $this->rateOurStayData;
    }

    /**
     * Set TripStayWinData
     *
     * @param TripStayWinData $tripStayWinData
     *
     * @return Property
     */
    public function setTripStayWinData(TripStayWinData $tripStayWinData)
    {
        $this->tripStayWinData = $tripStayWinData;

        return $this;
    }

    /**
     * Get TripStayWinData
     *
     * @return TripStayWinData
     */
    public function getTripStayWinData()
    {
        return $this->tripStayWinData;
    }

    /**
     * Set smsEnabled
     *
     * @param boolean $smsEnabled
     * @return $this
     */
    public function setSmsEnabled($smsEnabled)
    {
        $this->smsEnabled = $smsEnabled;

        return $this;
    }

    /**
     * Get smsEnabled
     *
     * @return boolean
     *
     * @deprecated Use hasSmsEnabled() instead
     */
    public function getSmsEnabled()
    {
        return $this->smsEnabled;
    }

    /**
     * Get smsEnabled
     *
     * @return boolean
     */
    public function hasSmsEnabled()
    {
        return $this->smsEnabled;
    }

    /**
     * Set LocalEvent
     *
     * @param LocalEvent $localEvent
     *
     * @return Property
     */
    public function setLocalEvent(LocalEvent $localEvent)
    {
        $this->localEvents[] = $localEvent;

        return $this;
    }

    /**
     * Get LocalEvent
     *
     * @return ArrayCollection
     */
    public function getLocalEvents()
    {
        return $this->localEvents;
    }

    /**
     * Set ResolveSetting
     *
     * @param ResolveSetting $resolveSetting
     *
     * @return Property
     */
    public function setResolveSetting(ResolveSetting $resolveSetting)
    {
        $this->resolveSetting = $resolveSetting;

        return $this;
    }

    /**
     * Get ResolveSetting
     *
     * @return ResolveSetting
     */
    public function getResolveSetting()
    {
        return $this->resolveSetting;
    }

    /**
     * Update timestamps before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new DateTime());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime());
        }

        if ($this->getHash() == null) {
            $this->setHash(hash("crc32b", $this->getAxNumber()));
        }
    }

    /*
     * Slugify
     *
     * @param $string
     * @return mixed|string
     */
    private function slugify($string)
    {
        $slug = str_replace('.', '', $string);
        $slug = preg_replace('/[^a-z0-9\/]/i', '-', strtolower($slug));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = str_replace('/', '_', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
