<?php

namespace TMG\Api\UserBundle\Entity;

use DateTime;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use TMG\Api\ApiBundle\Entity\HotelRevenueCalculation;
use TMG\Api\ApiBundle\Entity\Property;
use TMG\Api\ApiBundle\Entity\Books;
use TMG\Api\ApiBundle\Entity\ResolveContractorInvoice;
use TMG\Api\ApiBundle\Entity\ResolveResponse;
use TMG\Api\ApiBundle\Entity\ResolveResponseRating;
use TMG\Api\ApiBundle\Entity\TravelTypes;
use TMG\Api\ApiBundle\Entity\Country;
use TMG\Api\ApiBundle\Entity\State;
use TMG\Api\ApiBundle\Entity\PostalCode;
use TMG\Api\ApiBundle\Entity\Address;
use JMS\Serializer\Annotation as Serializer;

/**
 * User
 *
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="TMG\Api\UserBundle\Entity\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("all")
 */
class User extends BaseUser
{
    //Roles
    const ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";
    const API_ADMIN = "API_ADMIN";
    const ROLE_USER = "ROLE_USER";
    const READ_ONLY = "READ_ONLY";
    const NEW_USER = "NEW_USER";
    const HC_ADMIN = "HC_ADMIN";
    const SALES = "SALES";
    const HC_USER = "HC_USER";
    const ADMIN = "ADMIN";
    const MANAGEMENT = "MANAGEMENT";
    const MARKETING = "MARKETING";
    const COMPOSER = "COMPOSER";
    const INTERNAL = "INTERNAL";
    const HOTELIER = "HOTELIER";
    const CLERK = "CLERK";
    const ANONYMOUS = "ANONYMOUS";
    const CONTRACTOR = "CONTRACTOR";

    const NOT_FOUND_MESSAGE = "Could not find user";
    const NOT_FOUND_MESSAGE_EMAIL = "Could not find user with email of %s";
    const NOT_FOUND_MESSAGE_HASH = "Could not find user with hash of %s";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "review_detail",
     * })
     */
    private $hash;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TMG\Api\ApiBundle\Entity\Books", inversedBy="users")
     * @ORM\JoinTable(name="user_books",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")}
     * )
     */
    protected $books;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TMG\Api\ApiBundle\Entity\TravelTypes", inversedBy="users")
     * @ORM\JoinTable(name="user_travel_types",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="travel_types_id", referencedColumnName="id")}
     * )
     */
    protected $travelTypes;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="users")
     * @ORM\JoinTable(name="user_properties",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="property_id", referencedColumnName="id")}
     * )
     */
    protected $properties;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="favorites")
     * @ORM\JoinTable(name="user_favorite_properties",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="property_id", referencedColumnName="id")}
     * )
     */
    protected $favorites;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TMG\Api\ApiBundle\Entity\ResolveResponse", mappedBy="user", cascade={"persist"})
     */
    protected $resolveResponse;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TMG\Api\ApiBundle\Entity\ResolveResponseRating",
     *     mappedBy="ratedBy", cascade={"persist"})
     */
    protected $resolveResponseRatingRatedBy;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TMG\Api\ApiBundle\Entity\ResolveResponseRating",
     *     mappedBy="proposedBy", cascade={"persist"})
     */
    protected $resolveResponseRatingProposedBy;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TMG\Api\ApiBundle\Entity\HotelRevenueCalculation", mappedBy="user")
     *
     */
    private $hotelRevenueCalculations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TMG\Api\ApiBundle\Entity\ResolveContractorInvoice",
     *     mappedBy="user", cascade={"persist"})
     */
    protected $resolveContractorInvoices;

    /**
     * @var string
     *
     * @ORM\Column(name="old_pass", type="string", length=255, nullable=true)
     */
    private $oldPass;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({
     *     "Default",
     *     "resolve_property",
     * })
     */
    private $fullName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tutorial", type="boolean", nullable=true)
     */
    private $tutorial;

    /**
     * @var boolean
     *
     * @ORM\Column(name="subscribed", type="boolean", nullable=true)
     *
     * @Serializer\Expose
     */
    private $subscribed;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="birth_date", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     *
     * @Serializer\Expose
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="household_members", type="integer", length=2, nullable=true)
     */
    private $householdMembers;

    /**
     * @var integer
     * @ORM\Column(name="household_children", type="integer", length=2, nullable=true)
     */
    private $householdChildren;

    /**
     * @var integer
     *
     * @ORM\Column(name="contractor_pay_scale", type="integer", length=2, nullable=true)
     *
     * @Serializer\Expose
     */
    private $contractorPayScale;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $phone;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $city;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\State", cascade={"persist"})
     * @ORM\JoinColumn(name="state", referencedColumnName="id")
     */
    private $state;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\Country", cascade={"persist"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;

    /**
     * @var PostalCode
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\ApiBundle\Entity\PostalCode", cascade={"persist"})
     * @ORM\JoinColumn(name="postal_id", referencedColumnName="id")
     */
    private $postalCode;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $updatedAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->books = new ArrayCollection();
        $this->travelTypes = new ArrayCollection();
        $this->properties = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->hotelRevenueCalculations = new ArrayCollection();
        $this->resolveResponse = new ArrayCollection();
        $this->resolveResponseRatingRatedBy = new ArrayCollection();
        $this->resolveResponseRatingProposedBy = new ArrayCollection();
        $this->resolveContractorInvoices = new ArrayCollection();
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
     * Get books
     *
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Set books
     *
     * @param ArrayCollection $v
     *
     * @return User
     */
    public function setBooks(ArrayCollection $v)
    {
        $this->books = $v;

        return $this;
    }

    /**
     * Add book
     *
     * @param $v
     *
     * @return User
     */
    public function addBook($v)
    {
        $this->books[] = $v;

        return $this;
    }

    /**
     * Remove book
     *
     * @param $v
     *
     * @return User
     */
    public function removeBook($v)
    {
        return $this->books->removeElement($v);
    }

    /**
     * Has book
     *
     * @param Books $book
     * @return boolean
     */
    public function hasBook(Books $book)
    {
        return $this->books->contains($book);
    }


    /**
     * Get travelTypes
     *
     */
    public function getTravelTypes()
    {
        return $this->travelTypes;
    }

    /**
     * Set travelTypes
     *
     * @param ArrayCollection $v
     *
     * @return User
     */
    public function setTravelTypes(ArrayCollection $v)
    {
        $this->travelTypes = $v;

        return $this;
    }

    /**
     * Add travelTypes
     *
     * @param $v
     *
     * @return User
     */
    public function addTravelType($v)
    {
        $this->travelTypes[] = $v;

        return $this;
    }

    /**
     * Remove travelTypes
     *
     * @param $v
     *
     * @return User
     */
    public function removeTravelType($v)
    {
        return $this->travelTypes->removeElement($v);
    }

    /**
     * Has travelType
     *
     * @param TravelTypes $travelType
     * @return boolean
     */
    public function hasTravelType(TravelTypes $travelType)
    {
        return $this->travelTypes->contains($travelType);
    }


    /**
     * Get Properties
     *
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set Properties
     *
     * @param ArrayCollection $v
     *
     * @return User
     */
    public function setProperties(ArrayCollection $v)
    {
        $this->properties = $v;

        return $this;
    }

    /**
     * Add Property
     *
     * @param $v
     *
     * @return User
     */
    public function addProperty($v)
    {
        $this->properties[] = $v;

        return $this;
    }

    /**
     * Remove Property
     *
     * @param $v
     *
     * @return User
     */
    public function removeProperty($v)
    {
        return $this->properties->removeElement($v);
    }

    /**
     * Has property
     *
     * @param Property $property
     * @return boolean
     */
    public function hasProperty(Property $property)
    {
        return $this->properties->contains($property);
    }

    /**
     * Set favorites
     *
     * @param ArrayCollection $favorites
     *
     * @return User
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
     * @param Property $favorite
     * @return User
     */
    public function addFavorite(Property $favorite)
    {
        $this->favorites[] = $favorite;

        return $this;
    }

    /**
     * Remove favorite
     *
     * @param Property $favorite
     * @return User
     */
    public function removeFavorite(Property $favorite)
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    /**
     * Has favorite
     *
     * @param Property $favorite
     * @return boolean
     */
    public function hasFavorite(Property $favorite)
    {
        return $this->favorites->contains($favorite);
    }

    /**
     * Set oldPass
     *
     * @param string $oldPass
     * @return User
     */
    public function setOldPass($oldPass)
    {
        $this->oldPass = $oldPass;

        return $this;
    }

    /**
     * Get oldPass
     *
     * @return string
     */
    public function getOldPass()
    {
        return $this->oldPass;
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set tutorial
     *
     * @param boolean $tutorial
     * @return User
     */
    public function setTutorial($tutorial)
    {
        $this->tutorial = $tutorial;

        return $this;
    }

    /**
     * Get tutorial
     *
     * @return boolean
     */
    public function getTutorial()
    {
        return $this->tutorial;
    }

    /**
     * Set subscribed
     *
     * @param boolean $subscribed
     * @return User
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;

        return $this;
    }

    /**
     * Get subscribed
     *
     * @return boolean
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }

    /**
     * Set birthDate
     *
     * @param DateTime $birthDate
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set householdMembers
     *
     * @param integer $householdMembers
     * @return User
     */
    public function setHouseholdMembers($householdMembers)
    {
        $this->householdMembers = $householdMembers;

        return $this;
    }

    /**
     * Get householdMembers
     *
     * @return integer
     */
    public function getHouseholdMembers()
    {
        return $this->householdMembers;
    }

    /**
     * Set householdChildren
     *
     * @param integer $householdChildren
     * @return User
     */
    public function setHouseholdChildren($householdChildren)
    {
        $this->householdChildren = $householdChildren;

        return $this;
    }

    /**
     * Get householdChildren
     *
     * @return integer
     */
    public function getHouseholdChildren()
    {
        return $this->householdChildren;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $this->formatPhone($phone);

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
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return User
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set state
     *
     * @param State $state
     * @return User
     */
    public function setState(State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set postalCode
     *
     * @param PostalCode $postalCode
     * @return User
     */
    public function setPostalCode(PostalCode $postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return PostalCode
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set address
     *
     * @param Address $address
     *
     * @return User
     */
    public function setAddress(Address $address)
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
     * Set createdAt
     *
     * @param DateTime $createdAt
     * @return User
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
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
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

        if ($this->firstName || $this->lastName) {
            $fName = $this->firstName.' '.$this->lastName;
            $this->setFullName($fName);
        }
    }

    /**
     * @param $phone
     * @return null|string
     */
    public function formatPhone($phone)
    {
        $cleanPhone = null;
        $phone = preg_replace('/\D/', '', $phone);

        switch (strlen($phone)) {
            case 10:
                $area = substr($phone, 0, 3);
                $first = substr($phone, 3, 3);
                $last = substr($phone, -4);
                $cleanPhone = '('.$area.') '.$first.'-'.$last;
                break;

            case 11:
                $phone = substr($phone, 1);
                $area = substr($phone, 0, 3);
                $first = substr($phone, 3, 3);
                $last = substr($phone, -4);
                $cleanPhone = '('.$area.') '.$first.'-'.$last;
                break;
        }

        return $cleanPhone;
    }

    /**
     * Get resolve response
     *
     * @return ArrayCollection
     */
    public function getResolveResponse()
    {
        return $this->resolveResponse;
    }

    /**
     * Add resolveResponse
     *
     * @param ResolveResponse $resolveResponse
     *
     * @return User
     */
    public function addResolveResponse(ResolveResponse $resolveResponse)
    {
        $this->resolveResponse[] = $resolveResponse;

        return $this;
    }

    /**
     * Remove resolveResponse
     *
     * @param ResolveResponse $resolveResponse
     */
    public function removeResolveResponse(ResolveResponse $resolveResponse)
    {
        $this->resolveResponse->removeElement($resolveResponse);
    }

    /**
     * Get hotel revenue calculations for this user
     *
     * @return ArrayCollection
     */
    public function getHotelRevenueCalculations()
    {
        return $this->hotelRevenueCalculations;
    }

    /**
     * Add hotelRevenueCalculation
     *
     * @param HotelRevenueCalculation $hotelRevenueCalculation
     *
     * @return User
     */
    public function addHotelRevenueCalculation(HotelRevenueCalculation $hotelRevenueCalculation)
    {
        $this->hotelRevenueCalculations[] = $hotelRevenueCalculation;

        return $this;
    }

    /**
     * Remove hotelRevenueCalculation
     *
     * @param HotelRevenueCalculation $hotelRevenueCalculation
     */
    public function removeHotelRevenueCalculation(HotelRevenueCalculation $hotelRevenueCalculation)
    {
        $this->hotelRevenueCalculations->removeElement($hotelRevenueCalculation);
    }

    /**
     * Get resolveResponseRatingRatedBy
     *
     * @return ArrayCollection
     */
    public function getResolveResponseRatingRatedBy()
    {
        return $this->resolveResponseRatingRatedBy;
    }

    /**
     * Get resolveResponseRatingProposedBy
     *
     * @return ArrayCollection
     */
    public function getResolveResponseRatingProposedBy()
    {
        return $this->resolveResponseRatingProposedBy;
    }

    /**
     * Set contractorPayScale
     *
     * @param integer $contractorPayScale
     *
     * @return User
     */
    public function setContractorPayScale($contractorPayScale)
    {
        $this->contractorPayScale = $contractorPayScale;

        return $this;
    }

    /**
     * Get contractorPayScale
     *
     * @return integer
     */
    public function getContractorPayScale()
    {
        return $this->contractorPayScale;
    }

    /**
     * Add resolveResponseRatingRatedBy
     *
     * @param ResolveResponseRating $resolveResponseRatingRatedBy
     *
     * @return User
     */
    public function addResolveResponseRatingRatedBy(ResolveResponseRating $resolveResponseRatingRatedBy)
    {
        $this->resolveResponseRatingRatedBy[] = $resolveResponseRatingRatedBy;

        return $this;
    }

    /**
     * Remove resolveResponseRatingRatedBy
     *
     * @param ResolveResponseRating $resolveResponseRatingRatedBy
     */
    public function removeResolveResponseRatingRatedBy(ResolveResponseRating $resolveResponseRatingRatedBy)
    {
        $this->resolveResponseRatingRatedBy->removeElement($resolveResponseRatingRatedBy);
    }

    /**
     * Add resolveResponseRatingProposedBy
     *
     * @param ResolveResponseRating $resolveResponseRatingProposedBy
     *
     * @return User
     */
    public function addResolveResponseRatingProposedBy(ResolveResponseRating $resolveResponseRatingProposedBy)
    {
        $this->resolveResponseRatingProposedBy[] = $resolveResponseRatingProposedBy;

        return $this;
    }

    /**
     * Remove resolveResponseRatingProposedBy
     *
     * @param ResolveResponseRating $resolveResponseRatingProposedBy
     */
    public function removeResolveResponseRatingProposedBy(ResolveResponseRating $resolveResponseRatingProposedBy)
    {
        $this->resolveResponseRatingProposedBy->removeElement($resolveResponseRatingProposedBy);
    }

    /**
     * Add resolveContractorInvoice
     *
     * @param ResolveContractorInvoice $resolveContractorInvoice
     *
     * @return User
     */
    public function addResolveContractorInvoice(ResolveContractorInvoice $resolveContractorInvoice)
    {
        $this->resolveContractorInvoices[] = $resolveContractorInvoice;

        return $this;
    }

    /**
     * Remove resolveContractorInvoice
     *
     * @param ResolveContractorInvoice $resolveContractorInvoice
     */
    public function removeResolveContractorInvoice(ResolveContractorInvoice $resolveContractorInvoice)
    {
        $this->resolveContractorInvoices->removeElement($resolveContractorInvoice);
    }

    /**
     * Get resolveContractorInvoice
     *
     * @return ArrayCollection
     */
    public function getResolveContractorInvoices()
    {
        return $this->resolveContractorInvoices;
    }

    /**
     * Update hash
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateHash()
    {
        if ($this->getHash() == null) {
            $this->setHash(hash("crc32b", $this->emailCanonical.$this->id));
        }
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return User
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
}
