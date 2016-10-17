<?php

namespace TMG\Api\LegacyBundle\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;
use TMG\Api\LegacyBundle\Entity\AbstractEntity;
use TMG\Api\LegacyBundle\Entity\Address;

/**
 * **Internal use**
 * La Quinta property information.
 *
 * @ORM\Entity
 * @ORM\Table(name="laquinta_properties")
 * @ORM\HasLifecycleCallbacks()
 */
abstract class LaQuintaProperty extends AbstractEntity
{
    /**
     * Hotel code recognized by LQ API
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     */
    protected $hotelCode;

    /**
     * Hotel name provided by the LQ API
     *
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * A url-friendly string derived from the property name
     * "Super 8 Motel" becomes "super-8-motel"
     *
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * Hotel description provided by the LQ API
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * Phone number provided by the LQ API
     *
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * URL Provided by LQ API
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $url;

    /**
     * How to display the rate to a user
     *
     * @ORM\Column(type="string", nullable=false);
     */
    protected $ratePretty;

    /**
     * @ORM\Column(type="string")
     */
    protected $rate;

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
     * Timestamp of record creation
     *
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Timestamp of last update
     *
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->hotelCode;
    }

    /**
     * Set hotel code
     *
     * @param integer $v
     *
     * @return LaQuintaProperty
     */
    public function setHotelCode($v)
    {
        $this->hotelCode = $v;

        return $this;
    }

    /**
     * Get hotel code
     *
     * @return integer
     */
    public function getHotelCode()
    {
        return $this->hotelCode;
    }

    /**
     * Set name
     *
     * @param string $v
     *
     * @return LaQuintaProperty
     */
    public function setName($v)
    {
        $this->name = $v;
        $this->setSlug($this->slugify());

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
     * Set slug
     *
     * @param string $v
     *
     * @return LaQuintaProperty
     */
    public function setSlug($v)
    {
        $this->slug = $v;

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
     * Sluggify string
     *
     * @return string
     */
    public function slugify()
    {
        $name = $this->name;

        return self::slugifyString("$name");
    }

    /**
     * Set description
     *
     * @param string $v
     *
     * @return LaQuintaProperty
     */
    public function setDescription($v)
    {
        $this->description = $v;

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
     * Set phone number
     *
     * @param string $v
     *
     * @return  LaQuintaProperty
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
     * Get phone number
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set URL
     *
     * @param string $v
     *
     * @return LaQuintaProperty
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
     * Get URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set presentable rate
     *
     * @param string $v
     *
     * @return LaQuintaProperty
     */
    public function setRatePretty($v)
    {
        $this->ratePretty = $v;

        return $this;
    }

    /**
     * Get presentable rate
     *
     * @return string
     */
    public function getRatePretty()
    {
        return $this->ratePretty;
    }

    /**
     * Set rate
     *
     * @param string $v
     *
     * @return LaQuintaProperty
     */
    public function setRate($v)
    {
        $this->rate = $v;
        $this->setRateType();
        $this->updateRatePretty();

        return $this;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set rate type
     *
     * @param string $v
     *
     * @return LaQuintaProperty
     */
    public function setRateType($v = 'dollar')
    {
        $this->rateType = $v;

        return $this;
    }

    /**
     * Get rate type
     *
     * @return string
     */
    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LaQuintaProperty
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
     *
     * @return LaQuintaProperty
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
     * Format a phone number
     *
     * @param  string $phone
     *
     * @return string|null
     */
    private function formatPhone($phone)
    {
        $cleanPhone = '';
        $phone = str_replace('-', '', $phone);
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('(', '', $phone);

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
     * @return string|null
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
     * Update timestamps before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
}
