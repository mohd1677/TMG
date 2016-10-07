<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HotelCouponsSpecificUserTrait
{
    /**
     * Hotel Coupons specific
     * @ORM\ManyToMany(targetEntity="Property", cascade={"persist"})
     *
     * @Assert\Valid
     */
    protected $favorites;

    /**
     * Hotel Coupons specific
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $zipCode;

    /**
     * Is this user subscribed to the Hotel Coupons newsletter.
     * Hotel Coupons specific
     *
     * @ORM\Column(type="boolean")
     */
    protected $isSubscribed;

    /**
     * Hotel Coupons specific
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $birthdate;

    /**
     * Hotel Coupons specific
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gender;

    /**
     * Number of household members.
     * Hotel Coupons specific
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $numHouseholdMembers;

    /**
     * Number of children in household.
     * Hotel Coupons specific
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $numChildrenInHousehold;

    /**
     * @ORM\ManyToMany(targetEntity="GuideBook", cascade={"persist"})
     */
    protected $travelInterests;

    /**
     * @ORM\ManyToMany(targetEntity="TravelType", cascade={"persist"})
     */
    protected $travelTypes;

    /**
     * Hotel Coupons Constructor
     */
    public function hotelCouponsConstructor()
    {
        $this->isSubscribed = false;
        $this->favorites = new ArrayCollection();
        $this->travelInterests = new ArrayCollection();
        $this->travelTypes = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setFavorites(ArrayCollection $v)
    {
        $this->favorites = $v;

        return $this;
    }

    /**
     * @param Property $p
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function addFavorite(Property $p)
    {
        $this->favorites[] = $p;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setZipCode($v)
    {
        $this->zipCode = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSubscribed()
    {
        return $this->isSubscribed;
    }

    /**
     * @param bool $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setIsSubscribed($v)
    {
        $this->isSubscribed = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setBirthdate(DateTime $v)
    {
        $this->birthdate = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setGender($v)
    {
        $this->gender = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumHouseholdMembers()
    {
        return $this->numHouseholdMembers;
    }

    /**
     * @param int $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setNumHouseholdMembers($v)
    {
        $this->numHouseholdMembers = $v;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumChildrenInHousehold()
    {
        return $this->numChildrenInHousehold;
    }

    /**
     * @param int $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setNumChildrenInHousehold($v)
    {
        $this->numChildrenInHousehold = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTravelInterests()
    {
        return $this->travelInterests;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setTravelInterests(ArrayCollection $v)
    {
        $this->travelInterests = $v;

        return $this;
    }

    /**
     * @param GuideBook $book
     */
    public function addTravelInterest(GuideBook $book)
    {
        $this->travelInterests[] = $book;
    }

    /**
     * @return ArrayCollection
     */
    public function getTravelTypes()
    {
        return $this->travelTypes;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return HotelCouponsSpecificUserTrait
     */
    public function setTravelTypes(ArrayCollection $v)
    {
        $this->travelTypes = $v;

        return $this;
    }

    /**
     * @param TravelType $type
     */
    public function addTravelType(TravelType $type)
    {
        $this->travelTypes[] = $type;
    }
}
