<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * A property represents a physical location that ties together
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\PropertyRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(columns={"sms_number"}),
 *     @ORM\Index(columns={"sms_daily_enabled"})
 * })
 */
class Property extends AbstractEntity
{
    protected static $extraJsonFields = ['distance'];
    public $distance;

    /**
     * SMS messages.
     */
    const SMS_NEW_RATE_REMINDER = 'Reply with a new rate at any time.';
    const SMS_SYSTEM_HELP_MSG = 'Reply with MENU for more information about using the system';
    const SMS_REMINDERS_OFF = 'Reminders are off.';
    const SMS_REMINDERS_ON = 'Reminders are on.';
    const SMS_NUMBER_NOT_IN_SYSTEM = 'I cannot find your phone number.';
    const SMS_INVALID_RATE_ERROR_MSG = 'I do not understand. Please reply with a valid rate, or reply MENU for help.';
    const SMS_MENU_OPTIONS_MSG = 'Reply with a rate, or a keyword from this list:';
    const SMS_MENU_OPTION_LIST = 'LIST - show your SMS property list';
    const SMS_MENU_OPTION_RATES = 'RATES - see current rates';
    const SMS_MENU_OPTION_MENU = 'MENU - see this menu';
    const SMS_MENU_OPTION_REMIND = 'REMIND - turn on/off daily reminder';
    const SMS_MENU_OPTION_STOP = 'STOP - disable SMS rate service messages';
    const SMS_MENU_OPTION_START = 'START - enable SMS rate service messages';
    const SMS_LOG_RATE_SET_MULTI = 'SMS Rate Set (Multi-Property)';
    const SMS_LOG_RATE_SET_SINGLE = 'SMS Rate Set (Single Property)';
    const SMS_LOG_PROPERTY_LIST_MULTI = 'SMS Property List (Pending Multi-Property Rate)';
    const SMS_LOG_PROPERTY_LIST = 'SMS Property List';
    const SMS_LOG_NOT_FOUND = 'SMS Phone Number Not Found';
    const SMS_LOG_EMPTY_MESSAGE = 'SMS Empty Message Received';
    const SMS_LOG_MENU = 'SMS Menu';
    const SMS_LOG_START = 'SMS Start Received';
    const SMS_LOG_STOP = 'SMS Stop Received';
    const SMS_LOG_STOP_AUTO = 'SMS Stop By Twilio';
    const SMS_LOG_RATE_LIST = 'SMS Rate List';
    const SMS_LOG_INVALID = 'SMS Invalid';
    const SMS_LOG_REMINDERS_ON = 'SMS Reminders On';
    const SMS_LOG_REMINDERS_OFF = 'SMS Reminders Off';
    const SMS_LOG_REMINDER = 'SMS Daily Reminder Sent';
    const SMS_LOG_WELCOME = 'SMS Welcome Sent';
    const SMS_LOG_FAX = 'SMS Fax Accepted';
    const SMS_LOG_FAX_MISSING = 'SMS Fax Number Missing (Rate Set)';
    const SMS_LOG_FAX_ERROR = 'SMS Fax Error';
    const SMS_RATE_PENDING = 'SMS Rate Pending';
    const SMS_PENDING_TIMEOUT = 15;

    /**
     * SMS Message menu.
     *
     * @var array
     */
    public static $smsMenu = [
        self::SMS_MENU_OPTIONS_MSG,
        self::SMS_MENU_OPTION_LIST,
        self::SMS_MENU_OPTION_RATES,
        self::SMS_MENU_OPTION_MENU,
        self::SMS_MENU_OPTION_REMIND,
        self::SMS_MENU_OPTION_START,
        self::SMS_MENU_OPTION_STOP
    ];

    /**
     * 8 character hexadecimal property identifier
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=8, nullable=false)
     */
    protected $id;

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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $axAccountNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $e1AccountNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $tollFreePhone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $tollFreeActive;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $currentAdLink;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $socialActive;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="properties", cascade={"persist"})
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id", nullable=true)
     */
    protected $brand;

    /**
     * It's a fax number for sms.
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Length(
     *     min="10",
     *     max="11",
     *     minMessage="Please enter a valid 10 digit fax number.",
     *     maxMessage="Please enter a valid 10 digit fax number."
     * )
     */
    protected $smsFax;

    /**
     * Internal account number
     * @ORM\Column(type="string", length=40, unique=true)
     * @Assert\Length(
     *      min = "2",
     *      max = "40",
     *      minMessage = "Account number must be at least {{ limit }} characters.",
     *      maxMessage = "Account number cannot be longer than {{ limit }} characters."
     * )
     */
    protected $accountNumber;

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
     * Custom featured amenities.
     *
     * @ORM\Column(type="array")
     */
    protected $featuredAmenities;

    /**
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id",  nullable=true)
     */
    protected $address;

    /**
     * @ORM\OneToOne(targetEntity="PropertyDescription", mappedBy="property", cascade={"persist"})
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="PropertyAdvertisement", mappedBy="property", cascade={"persist"})
     */
    protected $advertisements;

    /**
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist"})
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id",  nullable=true)
     */
    protected $billingAddress;

    /**
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="property")
     */
    protected $contracts;

    /**
     * @ORM\OneToMany(targetEntity="AxContract", mappedBy="property")
     */
    protected $axContracts;

    /**
     * @ORM\ManyToMany(targetEntity="Amenity")
     */
    protected $amenities;

    /**
     * @ORM\OneToMany(targetEntity="PropertyPhoto", mappedBy="property")
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="Analytic", mappedBy="property")
     */
    protected $analytics;

    /**
     * Phone number to be shown on advertisements
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\OneToOne(targetEntity="Video", inversedBy="property")
     */
    protected $video;

    /**
     * Phone number to reach a property representative at. Not to be
     * shown on advertisements. For that, use PropertyAdvertisement#phoneNumber
     *
     * @ORM\Column(type="string")
     */
    protected $accountPhone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="properties", cascade={"persist"})
     */
    protected $users;

    /**
     * @ORM\OneToOne(targetEntity="IhgProperty", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="hotel_code")
     */
    protected $ihgProperty;

    /**
     * @ORM\OneToOne(targetEntity="LaQuintaProperty", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="hotel_code")
     */
    protected $laQuintaProperty;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isLock;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $showOnline;

    /**
     * @ORM\Column(name="time_zone_id", type="string", nullable=true)
     */
    protected $timeZoneId;

    /**
     * SMS Number
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Length(
     *     min = 10,
     *     max = 10,
     *     minMessage = "Please enter a valid 10 digit phone number.",
     *     maxMessage = "Please enter a valid 10 digit phone number."
     * )
     */
    protected $smsNumber;

    /**
     * Whether or not this is an ESA (Extended Stay America) property.
     * This was implemented (against better judgement) in order to quickly determine whether or not a property
     * was an ESA property. It is used on HC search results and property details pages to customize the text
     * on the "Get Coupon" button and can be toggled in the "Edit Property Info" screen in MyTMG.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $esa;

    /**
     * Whether or not this property is an attraction
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $attraction;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $socialLink;

    /**
     * This url is for the "Online Rate" link
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $url;

    /**
     * SMS Contact
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $smsContact;

    /**
     * SMS Daily Enabled
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $smsDailyEnabled = false;

    /**
     * SMS Stop Date
     *
     * @var datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $smsStopDate;

    /**
     * Last SMS Send Date
     *
     * @var datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $smsSendDate;

    /**
     * Property constructor.
     */
    public function __construct()
    {
        $this->advertisements = new ArrayCollection();
        $this->featuredAmenities = [];
        $this->amenities = new ArrayCollection();
        $this->contracts = new ArrayCollection();
        $this->axContracts = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return boolean
     */
    public function isAttraction()
    {
        return $this->attraction;
    }

    /**
     * @param $attraction
     *
     * @return Property
     */
    public function setAttraction($attraction)
    {
        $this->attraction = $attraction;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEsa()
    {
        return $this->esa;
    }

    /**
     * @param $esa
     *
     * @return Property
     */
    public function setEsa($esa)
    {
        $this->esa = $esa;

        return $this;
    }

    /**
     * @return string
     */
    public function getSmsFax()
    {
        return $this->smsFax;
    }

    /**
     * @param string $smsFax
     *
     * @return Property
     */
    public function setSmsFax($smsFax)
    {
        $this->smsFax = $this->formatPhoneNumber($smsFax);

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     */
    public function setId($v)
    {
        $this->id = $v;
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
     * @return Property
     */
    public function setAccountNumber($v)
    {
        $this->accountNumber = $v;

        if (!$this->id) {
            $this->id = hash("crc32b", $v);
        }

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
     * @return Property
     */
    public function setName($v)
    {
        $this->name = $v;

        if ($this->getAddress()) {
            $this->setSlug($this->slugify());
        }

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
     * @return Property
     */
    public function setSlug($v)
    {
        $this->slug = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function slugify()
    {
        $name = $this->name;

        return self::slugifyString("$name");
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
     * @return Property
     */
    public function setFeaturedAmenities(array $v)
    {
        $this->featuredAmenities = $v;

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
     * @param Address|null $v
     *
     * @return Property
     */
    public function setAddress($v = null)
    {
        $this->address = $v;

        if ($this->getName()) {
            $this->setSlug($this->slugify());
        }

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
     * @return Property
     */
    public function setDescription(PropertyDescription $v)
    {
        $this->description = $v;
        $v->setProperty($this);

        return $this;
    }

    /**
     * @return Property
     */
    public function removeDescription()
    {
        $this->description = null;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdvertisements()
    {
        return $this->advertisements;
    }

    /**
     * @param PropertyAdvertisement $v
     *
     * @return Property
     */
    public function addAdvertisement(PropertyAdvertisement $v)
    {
        $this->advertisements->add($v);

        return $this;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return Property
     */
    public function setAdvertisements(ArrayCollection $v)
    {
        $this->advertisements = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLatestExpiredAdvertisement()
    {
        $now = new \DateTime('now');
        $criteria = Criteria::create()
            ->where(Criteria::expr()->lte('endDate', $now))
            ->orderBy(['endDate' => Criteria::DESC]);

        return $this->advertisements->matching($criteria)->first();
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
     * @return Property
     */
    public function setAmenities(ArrayCollection $v)
    {
        $this->amenities = $v;
        return $this;
    }

    /**
     * @param Amenity $v
     *
     * @return Property
     */
    public function addAmenity(Amenity $v)
    {
        $this->amenities[] = $v;

        return $this;
    }

    /**
     * @param Amenity $v
     *
     * @return Property
     */
    public function removeAmenity(Amenity $v)
    {
        $this->amenities->removeElement($v);

        return $this;
    }

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param Address|null $v
     *
     * @return Property
     */
    public function setBillingAddress($v = null)
    {
        $this->billingAddress = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return Property
     */
    public function setContracts(ArrayCollection $v)
    {
        $this->contracts = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAxContracts()
    {
        return $this->axContracts;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return Property
     */
    public function setAxContracts(ArrayCollection $v)
    {
        $this->axContracts = $v;

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
     */
    public function setPhotos(ArrayCollection $v)
    {
        $this->photos = $v;
    }

    /**
     * @param PropertyPhoto $v
     */
    public function addPhoto(PropertyPhoto $v)
    {
        $this->photos[] = $v;
    }

    /**
     * @return Analytic
     */
    public function getAnalytics()
    {
        return $this->analytics;
    }

    /**
     * @param $value
     */
    public function setAnalytics($value)
    {
        $this->analytics[] = $value;
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
     * @return Property
     */
    public function setPhone($v)
    {
        $this->phone = $v;

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
     * @return Property
     */
    public function setVideo(Video $v)
    {
        $this->video = $v;

        return $this;
    }

    /**
     * @return Property
     */
    public function removeVideo()
    {
        $this->video = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountPhone()
    {
        return $this->accountPhone;
    }

    /**
     * @param string $v
     *
     * @return Property
     */
    public function setAccountPhone($v)
    {
        $this->accountPhone = $v;

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
     * @return Property
     */
    public function setEmail($v)
    {
        $this->email = $v;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param $v
     *
     * @return Property
     */
    public function setUsers($v)
    {
        $this->users = $v;

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
     * @return Property
     */
    public function setIhgProperty(IhgProperty $v)
    {
        $this->ihgProperty = $v;

        return $this;
    }

    /**
     * @return Property
     */
    public function removeIhgProperty()
    {
        $this->ihgProperty = null;

        return $this;
    }

    /**
     * @return LaQuintaProperty
     */
    public function getLaQuintaProperty()
    {
        return $this->laQuintaProperty;
    }

    /**
     * @param LaQuintaProperty $v
     *
     * @return Property
     */
    public function setLaQuintaProperty(LaQuintaProperty $v)
    {
        $this->laQuintaProperty = $v;

        return $this;
    }

    /**
     * @return Property
     */
    public function removeLaQuintaProperty()
    {
        $this->laQuintaProperty = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getName() . ' (' . $this->getAccountNumber() . ')';
    }

    /**
     * Function to convert array of FeaturedAmenities to a string.
     *
     * @return string
     */
    public function getFeaturedAmenitiesString()
    {
        $featuredAmenitiesString = '';
        foreach ($this->featuredAmenities as $featuredAmenitie) {
            if ($featuredAmenitie === end($this->featuredAmenities)) {
                $featuredAmenitiesString = $featuredAmenitiesString . $featuredAmenitie;
            } else {
                $featuredAmenitiesString = $featuredAmenitiesString . $featuredAmenitie . PHP_EOL;
            }
        }

        return $featuredAmenitiesString;
    }

    /**
     * Function to convert a string to an array of FeaturedAmenities.
     *
     * @param string $v
     *
     * @return Property
     */
    public function setFeaturedAmenitiesString($v)
    {
        $featuredAmenities = explode(PHP_EOL, $v);
        $this->featuredAmenities = $featuredAmenities;

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
     * @return Property
     */
    public function setUrl($v)
    {
        if ($v) {
            //make sure that url has leading of "http://"
            if (substr($v, 0, 4) == 'http') {
                $this->url = $v;
                return $this;
            } else {
                $this->url = 'http://' . $v;
                return $this;
            }
        } else {
            $this->url = null;
            return $this;
        }
    }

    /**
     * @return bool
     */
    public function getIsLock()
    {
        return $this->isLock;
    }

    /**
     * @param bool $v
     *
     * @return Property
     */
    public function setIsLock($v)
    {
        $this->isLock = $v;
        return $this;
    }


    /**
     * @return bool
     */
    public function getShowOnline()
    {
        return $this->showOnline;
    }

    /**
     * @param bool $v
     *
     * @return Property
     */
    public function setShowOnline($v)
    {
        $this->showOnline = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getSocialLink()
    {
        return $this->socialLink;
    }

    /**
     * @param string $v
     *
     * @return Property
     */
    public function setSocialLink($v)
    {
        $this->socialLink = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
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
        $this->updatedAt = new \DateTime();
    }


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Property
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
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getAxAccountNumber()
    {
        return $this->axAccountNumber;
    }

    /**
     * @param string $v
     *
     * @return Property
     */
    public function setAxAccountNumber($v)
    {
        $this->axAccountNumber = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getE1AccountNumber()
    {
        return $this->e1AccountNumber;
    }

    /**
     * @param string $v
     *
     * @return Property
     */
    public function setE1AccountNumber($v)
    {
        $this->e1AccountNumber = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getTollFreePhone()
    {
        return $this->tollFreePhone;
    }

    /**
     * @param string $v
     *
     * @return Property
     */
    public function setTollFreePhone($v)
    {
        $this->tollFreePhone = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getTollFreeActive()
    {
        return $this->tollFreeActive;
    }

    /**
     * @param string $v
     *
     * @return Property
     */
    public function setTollFreeActive($v)
    {
        $this->tollFreeActive = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentAdLink()
    {
        return $this->currentAdLink;
    }

    /**
     * @param string $v
     *
     * @return Property
     */
    public function setCurrentAdLink($v)
    {
        if ($v) {
            //make sure that url has leading of "http://"
            if (substr($v, 0, 4) == 'http') {
                $this->currentAdLink = $v;
                return $this;
            } else {
                $this->currentAdLink = 'http://' . $v;
                return $this;
            }
        } else {
            $this->currentAdLink = null;
            return $this;
        }
    }

    /**
     * @return bool
     *
     * @todo This should be called `isSociallyActive()`
     */
    public function getSocialActive()
    {
        return $this->socialActive;
    }

    /**
     * @param bool $v
     *
     * @return Property
     */
    public function setSocialActive($v)
    {
        $this->socialActive = $v;

        return $this;
    }

    /**
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $v
     *
     * @return Property
     */
    public function setBrand(Brand $v)
    {
        $this->brand = $v;

        return $this;
    }

    /**
     * Get SMS Contact
     *
     * @return string
     */
    public function getSmsContact()
    {
        return $this->smsContact;
    }

    /**
     * Set SMS Contact
     *
     * @param string $smsContact
     *
     * @return Property
     */
    public function setSmsContact($smsContact)
    {
        $this->smsContact = $smsContact;

        return $this;
    }

    /**
     * Get SMS Number
     *
     * @return string
     *
     */
    public function getSmsNumber()
    {
        return $this->smsNumber;
    }

    /**
     * Set SMS Number
     *
     * @param string $smsNumber
     *
     * @return Property
     */
    public function setSmsNumber($smsNumber)
    {

        $smsNumber = $this->formatPhoneNumber($smsNumber);

        $this->smsNumber = $smsNumber;

        return $this;
    }

    /**
     * Get SMS Daily Enabled
     *
     * @return boolean
     */
    public function getSmsDailyEnabled()
    {
        return $this->smsDailyEnabled;
    }

    /**
     * Set SMS Daily Enabled
     *
     * @param boolean $smsDailyEnabled
     *
     * @return Property
     */
    public function setSmsDailyEnabled($smsDailyEnabled)
    {
        $this->smsDailyEnabled = $smsDailyEnabled;

        return $this;
    }

    /**
     * Get SMS Stop Date
     *
     * @return datetime
     */
    public function getSmsStopDate()
    {
        return $this->smsStopDate;
    }

    /**
     * Set SMS Stop Date
     *
     * @param datetime $smsStopDate
     *
     * @return Property
     */
    public function setSmsStopDate($smsStopDate)
    {
        $this->smsStopDate = $smsStopDate;

        return $this;
    }

    /**
     * Get Last SMS Send Date
     *
     * @return datetime
     */
    public function getSmsSendDate()
    {
        return $this->smsSendDate;
    }

    /**
     * Set Last SMS Send Date
     *
     * @param datetime $smsSendDate
     *
     * @return Property
     */
    public function setSmsSendDate($smsSendDate)
    {
        $this->smsSendDate = $smsSendDate;

        return $this;
    }

    /**
     * Get timezone id
     *
     * @return string
     */
    public function getTimeZoneId()
    {
        return $this->timeZoneId;
    }

    /**
     * Set timezone id
     *
     * @param string $timeZoneId
     *
     * @return Property
     */
    public function setTimeZoneId($timeZoneId)
    {
        $this->timeZoneId = $timeZoneId;

        return $this;
    }

    /**
     * Strip non numeric characters and remove a leading 1
     * this will need to be reworked if we send sms to
     * numbers outside of us and canada
     *
     * @param $number
     * @return mixed
     */
    public function formatPhoneNumber($number)
    {
        $number = preg_replace('/\D/', '', $number);
        $number = preg_replace('/^1/', '', $number);

        return $number;
    }
}
