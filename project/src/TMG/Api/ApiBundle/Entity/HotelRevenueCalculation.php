<?php

namespace TMG\Api\ApiBundle\Entity;

use \DateTime;
use TMG\Api\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Address
 *
 * @ORM\Table(name="HotelRevenueCalculations")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\HotelRevenueCalculationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class HotelRevenueCalculation
{

    public static $requiredPostFields = [
        'hotelName' => true,
        'hotelCity' => false,
        'hotelState' => false,
        'numberOfRooms' => false,
        'annualOccupancy' => false,
        'annualAdr' => false,
        'otaFeesPercent' => false,
        'revParIncrease' => false,
        'divertExistingFlowFromOta' => false,
        'hotelFinderRoomsPerNight' => false,
        'organicSearchRoomsPerNight' => false,
    ];

    const NOT_FOUND_MESSAGE = "Hotel Revenue Calculation %s was not found.";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_name", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $hotelName;

    /**
     * @var int|string
     *
     * @ORM\Column(name="number_of_rooms", type="integer")
     *
     * @Serializer\Expose
     */
    private $numberOfRooms;

    /**
     * @var int|string
     *
     * @ORM\Column(name="annual_occupancy", type="float")
     *
     * @Serializer\Expose
     */
    private $annualOccupancy;

    /**
     * @var int|string
     *
     * @ORM\Column(name="annual_adr", type="float")
     *
     * @Serializer\Expose
     */
    private $annualAdr;

    /**
     * @var float|string
     *
     * @ORM\Column(name="ota_fees", type="float")
     *
     * @Serializer\Expose
     */
    private $otaFeesPercent = 65;

    /**
     * @var float|string
     *
     * @ORM\Column(name="rev_par_increase", type="float")
     *
     * @Serializer\Expose
     */
    private $revParIncrease = 11;

    /**
     * @var float|string
     *
     * @ORM\Column(name="divert_existing_flow_from_ota", type="float")
     *
     * @Serializer\Expose
     */
    private $divertExistingFlowFromOta = 10;

    /**
     * @var int|string
     *
     * @ORM\Column(name="hotel_finder_rooms_per_night", type="integer")
     *
     * @Serializer\Expose
     */
    private $hotelFinderRoomsPerNight = 1;

    /**
     * @var int|string
     *
     * @ORM\Column(name="organic_search_rooms_per_night", type="integer")
     *
     * @Serializer\Expose
     */
    private $organicSearchRoomsPerNight = 1;

    /**
     * @var float|string
     *
     * @Serializer\Expose
     */
    private $annualRevenue = 0.00;

    /**
     * @var float|string
     *
     * @Serializer\Expose
     */
    private $revPar = 0.00;

    /**
     * @var float|string
     *
     * @Serializer\Expose
     */
    private $otaFees = 0.00;

    /**
     * @var float|string
     *
     * @Serializer\Expose
     */
    private $revenueAfterOta = 0.00;

    /**
     * @var float
     *
     * @Serializer\Expose
     */
    private $otaPercentOfRevenueCost = 0.00;

    /**
     * @var float
     *
     * @Serializer\Expose
     */
    private $increaseInRevParAmount = 0.00;

    /**
     * @var float|string
     *
     * @Serializer\Expose
     */
    private $revParIncreaseImpactOnRevenue = 0.00;

    /**
     * @var float|string
     *
     * @Serializer\Expose
     */
    private $totalGainFromRevParIncrease = 0.00;

    /**
     * @var float
     *
     * @Serializer\Expose
     */
    private $hotelFinderIncrease = 0.00;

    /**
     * @var float
     *
     * @Serializer\Expose
     */
    private $organicSearchIncrease = 0.00;

    /**
     * @var float
     *
     * @Serializer\Expose
     */
    private $otaSavings = 0.00;

    /**
     * @var float
     *
     * @Serializer\Expose
     */
    private $newRevenue = 0.00;

    /**
     * @var float
     *
     * @Serializer\Expose
     */
    private $grandTotal = 0.00;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TMG\Api\UserBundle\Entity\User", inversedBy="hotelRevenueCalculations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     *
     * @Serializer\Expose
     */
    private $updatedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string $value
     * @return $this
     */
    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getHotelName()
    {
        return $this->hotelName;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setHotelName($value)
    {
        $this->hotelName = $value;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getNumberOfRooms()
    {
        return $this->numberOfRooms;
    }

    /**
     * @param int|string $value
     * @return $this
     */
    public function setNumberOfRooms($value)
    {
        $this->numberOfRooms = (int)$value;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getAnnualOccupancy()
    {
        return $this->annualOccupancy;
    }

    /**
     * @param int|string $value
     * @return $this
     */
    public function setAnnualOccupancy($value)
    {
        $this->annualOccupancy = (float)$value;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getAnnualAdr()
    {
        return $this->annualAdr;
    }

    /**
     * @param int|string $value
     * @return $this
     */
    public function setAnnualAdr($value)
    {
        $this->annualAdr = (float)$value;
        return $this;
    }

    /**
     * @return float|string
     */
    public function getOtaFeesPercent()
    {
        return $this->otaFeesPercent;
    }

    /**
     * @param float|string $value
     * @return $this
     */
    public function setOtaFeesPercent($value)
    {
        $this->otaFeesPercent = (float)$value;
        return $this;
    }

    /**
     * @return float|string
     */
    public function getRevParIncrease()
    {
        return $this->revParIncrease;
    }

    /**
     * @param float|string $value
     * @return $this
     */
    public function setRevParIncrease($value)
    {
        $this->revParIncrease = (float)$value;
        return $this;
    }

    /**
     * @return float|string
     */
    public function getDivertExistingFlowFromOta()
    {
        return $this->divertExistingFlowFromOta;
    }

    /**
     * @param float|string $value
     * @return $this
     */
    public function setDivertExistingFlowFromOta($value)
    {
        $this->divertExistingFlowFromOta = (float)$value;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getHotelFinderRoomsPerNight()
    {
        return $this->hotelFinderRoomsPerNight;
    }

    /**
     * @param int|string $value
     * @return $this
     */
    public function setHotelFinderRoomsPerNight($value)
    {
        $this->hotelFinderRoomsPerNight = (int)$value;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getOrganicSearchRoomsPerNight()
    {
        return $this->organicSearchRoomsPerNight;
    }

    /**
     * @param int|string $value
     * @return $this
     */
    public function setOrganicSearchRoomsPerNight($value)
    {
        $this->organicSearchRoomsPerNight = (int)$value;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $value
     * @return $this
     */
    public function setUser(User $value)
    {
        $this->user = $value;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $value
     * @return $this
     */
    public function setCreatedAt(DateTime $value)
    {
        $this->createdAt = $value;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $value
     * @return $this
     */
    public function setUpdatedAt(DateTime $value)
    {
        $this->updatedAt = $value;
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
        $this->setUpdatedAt(new DateTime());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime());
        }
    }

    public function calculateValues()
    {
        $this->calculateAnnualRevenue();
        $this->calculateRevPar();
        $this->calculateOtaFee();
        $this->calculateRevenueAfterOta();
        $this->calculateOtaPercentOfRevenueCost();
        $this->calculateIncreaseInRevPar();
        $this->calculateRevParIncreaseImpactOnRevenue();
        $this->calculateTotalGainFromRevParIncrease();
        $this->calculateHotelFinderIncrease();
        $this->calculateOrganicSearchIncrease();
        $this->calculateOtaSavings();
        $this->calculateNewRevenue();
        $this->calculateGrandTotal();

        //now that all values have been calculated
        //round those values. Needs to be done in this order
        $this->annualRevenue = round($this->annualRevenue, 2);
        $this->revPar = round($this->revPar, 2);
        $this->otaFees = round($this->otaFees, 2);
        $this->otaPercentOfRevenueCost = round($this->otaPercentOfRevenueCost, 2);
        $this->increaseInRevParAmount = round($this->increaseInRevParAmount, 2);
        $this->revParIncreaseImpactOnRevenue = round($this->revParIncreaseImpactOnRevenue, 2);
        $this->hotelFinderIncrease = round($this->hotelFinderIncrease, 2);
        $this->organicSearchIncrease = round($this->organicSearchIncrease, 2);
        $this->otaSavings = round($this->otaSavings, 2);
        $this->revenueAfterOta = round($this->revenueAfterOta, 2);
        $this->totalGainFromRevParIncrease = round($this->totalGainFromRevParIncrease, 2);
        $this->newRevenue = round($this->newRevenue, 2);
        $this->grandTotal = round($this->grandTotal, 2);
    }

    private function calculateAnnualRevenue()
    {
        if (!empty($this->annualAdr) && !empty($this->annualOccupancy) && !empty($this->numberOfRooms)) {
            $this->annualRevenue = $this->annualAdr * ($this->annualOccupancy/100) * $this->numberOfRooms * 365;
        }
    }

    private function calculateRevPar()
    {
        if (!empty($this->annualAdr) && !empty($this->annualOccupancy)) {
            $this->revPar = $this->annualAdr * ($this->annualOccupancy/100);
        }
    }

    private function calculateOtaFee()
    {
        if (!empty($this->otaFeesPercent)) {
            $this->otaFees = $this->annualRevenue * ($this->otaFeesPercent/100) * .2;
        }
    }

    private function calculateRevenueAfterOta()
    {
        $this->revenueAfterOta = $this->annualRevenue - $this->otaFees;
    }

    private function calculateOtaPercentOfRevenueCost()
    {
        if ($this->annualRevenue != 0) {
            $this->otaPercentOfRevenueCost = $this->otaFees / $this->annualRevenue * 100;
        }
    }

    private function calculateIncreaseInRevPar()
    {
        $this->increaseInRevParAmount = ($this->revPar * $this->revParIncrease / 100) + $this->revPar;
    }

    private function calculateRevParIncreaseImpactOnRevenue()
    {
        if (!empty($this->numberOfRooms)) {
            $this->revParIncreaseImpactOnRevenue = $this->numberOfRooms * $this->increaseInRevParAmount * 365;
        }
    }

    private function calculateTotalGainFromRevParIncrease()
    {
        $this->totalGainFromRevParIncrease = $this->revParIncreaseImpactOnRevenue - $this->annualRevenue;
    }

    private function calculateHotelFinderIncrease()
    {
        if (!empty($this->annualAdr) && !empty($this->hotelFinderRoomsPerNight)) {
            $this->hotelFinderIncrease = $this->annualAdr * $this->hotelFinderRoomsPerNight * 365;
        }
    }

    private function calculateOrganicSearchIncrease()
    {
        if (!empty($this->annualAdr) && !empty($this->organicSearchRoomsPerNight)) {
            $this->organicSearchIncrease = $this->annualAdr * $this->organicSearchRoomsPerNight * 365;
        }
    }

    private function calculateOtaSavings()
    {
        if (!empty($this->divertExistingFlowFromOta)) {
            $this->otaSavings = $this->otaFees * ($this->divertExistingFlowFromOta / 100);
        }
    }

    private function calculateNewRevenue()
    {
        $this->newRevenue = $this->hotelFinderIncrease + $this->organicSearchIncrease;
    }

    private function calculateGrandTotal()
    {
        $this->grandTotal = $this->otaSavings + $this->newRevenue + $this->totalGainFromRevParIncrease;
    }
}
