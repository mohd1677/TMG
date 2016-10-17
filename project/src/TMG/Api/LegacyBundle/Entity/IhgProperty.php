<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Properties pulled and cached by the IHG Api
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class IhgProperty extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5)
     */
    protected $hotelCode;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * A url-friendly string containing the city, state, and property name
     * "Super 8 Motel" becomes "super-8-motel"
     *
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

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
     * @ORM\Column(type="string", nullable=false);
     */
    protected $ratePretty;

    /**
     * @ORM\Column(type="string")
     */
    protected $rate;

    /**
     * @ORM\Column(type="string")
     */
    protected $brand;

    /**
     * @ORM\Column(type="string")
     */
    protected $image;

    /**
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * For online advertisements, this url is for the "Online Rate" link
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id",  nullable=false)
     */
    protected $address;

    /**
     * DateTime the advertisement expires
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $endDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->hotelCode;
    }

    /**
     * @return string
     */
    public function getHotelCode()
    {
        return $this->hotelCode;
    }

    /**
     * @param string $v
     *
     * @return IhgProperty
     */
    public function setHotelCode($v)
    {
        $this->hotelCode = $v;

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
     * @return IhgProperty
     */
    public function setName($v)
    {
        $this->name = $v;
        $this->setSlug($this->slugify());

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
     * @return IhgProperty
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $v
     *
     * @return IhgProperty
     */
    public function setDescription($v)
    {
        $this->description = $v;

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
     * @return IhgProperty
     */
    public function setRateType($v = 'dollar')
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
     * @return IhgProperty
     */
    public function setRatePretty($v)
    {
        $this->ratePretty = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param string $v
     *
     * @return IhgProperty
     */
    public function setRate($v)
    {
        $this->rate = $v;
        $this->setRateType();
        $this->updateRatePretty();

        return $this;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $v
     *
     * @return IhgProperty
     */
    public function setBrand($v)
    {
        $this->brand = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $v
     *
     * @return IhgProperty
     */
    public function setImage($v)
    {
        $this->image = $v;

        return $this;
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
     * @return IhgProperty
     */
    public function setPhone($v)
    {
        $format = $this->formatPhone($v);

        if ($format) {
            $this->phone = $format;
        } else {
            $this->phone = $v;
        }

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
     * @return IhgProperty
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
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $v
     *
     * @return IhgProperty
     */
    public function setAddress(Address $v)
    {
        $this->address = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $v
     *
     * @return IhgProperty
     */
    public function setEndDate(\DateTime $v)
    {
        $this->endDate = $v;

        return $this;
    }

    /**
     * @return void
     */
    public function updateRatePretty()
    {
        $pretty = self::generateRatePretty($this->rate);

        if ($pretty) {
            $this->ratePretty = $pretty;
        }
    }

    /**
     * @param string $rateValue
     *
     * @return string
     */
    public static function generateRatePretty($rateValue)
    {

        $prettyString = '$0.00';

        setlocale(LC_MONETARY, 'en_US.UTF-8');

        if ($rateValue != null) {
            $prettyString = money_format('%.2n', $rateValue);
        }

        return $prettyString;
    }


    /**
     * @param $phone
     *
     * @return string|null
     */
    private function formatPhone($phone)
    {
        $cleanPhone = '';
        $phone = str_replace('-', '', $phone);
        $phone = str_replace(' ', '', $phone);


        if (strlen($phone) == 10) {
            $area = substr($phone, 0, 3);
            $first = substr($phone, 3, 3);
            $last = substr($phone, -4);
            $cleanPhone = '('.$area.') '.$first.'-'.$last;
        } elseif (strlen($phone) == 11) {
            $phone = substr($phone, 1);
            $area = substr($phone, 0, 3);
            $first = substr($phone, 3, 3);
            $last = substr($phone, -4);
            $cleanPhone = '('.$area.') '.$first.'-'.$last;
        }

        if (!$cleanPhone) {
            return null;
        }

        return $cleanPhone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return IhgProperty
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
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return IhgProperty
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
}
