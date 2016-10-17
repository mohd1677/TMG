<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IHGProperty
 *
 * @ORM\Table(name="IHGProperties")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\IHGPropertyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class IHGProperty
{
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
     * @ORM\Column(name="hotel_code", type="string", length=6, unique=true)
     */
    private $hotelCode;

    /**
     * @ORM\OneToOne(targetEntity="TMG\Api\ApiBundle\Entity\Property", inversedBy="ihgProperty")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", nullable=true)
     **/
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="rate", type="string", length=10)
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(name="rate_type", type="string", length=255)
     */
    private $rateType;

    /**
     * @var string
     *
     * @ORM\Column(name="rate_pretty", type="string", length=255)
     */
    private $ratePretty;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetime")
     */
    private $expires;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var brand
     *
     * @ORM\ManyToOne(targetEntity="Brand", cascade={"persist"})
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

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
     * Set hotelCode
     *
     * @param string $hotelCode
     * @return IHGProperty
     */
    public function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;

        return $this;
    }

    /**
     * Get hotelCode
     *
     * @return string
     */
    public function getHotelCode()
    {
        return $this->hotelCode;
    }

    /**
     * Set property
     *
     * @param Property $property
     * @return IHGProperty
     */
    public function setProperty(Property $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * remove property
     *
     * @return IHGProperty
     */
    public function removeProperty()
    {
        $this->property = null;

        return $this;
    }



    /**
     * Set address
     *
     * @param Address $address
     * @return IHGProperty
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return IHGProperty
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setSlug($this->slugify($name));

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
     * Set description
     *
     * @param string $description
     * @return IHGProperty
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
     * Set rate
     *
     * @param string $rate
     * @return IHGProperty
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

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
     * Set rateType
     *
     * @param string $rateType
     * @return IHGProperty
     */
    public function setRateType($rateType)
    {
        $this->rateType = $rateType;

        return $this;
    }

    /**
     * Get rateType
     *
     * @return string
     */
    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * Set ratePretty
     *
     * @param string $ratePretty
     * @return IHGProperty
     */
    public function setRatePretty($ratePretty)
    {
        $this->ratePretty = $ratePretty;

        return $this;
    }

    /**
     * Get ratePretty
     *
     * @return string
     */
    public function getRatePretty()
    {
        return $this->ratePretty;
    }

    /**
     * update ratePretty
     *
     * @return string
     */
    public function updateRatePretty()
    {
        $this->ratePretty = $this->generateRatePretty();
        return $this->ratePretty;
    }


    /**
     * Set image
     *
     * @param string $image
     * @return IHGProperty
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return IHGProperty
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
     * Set slug
     *
     * @param string $slug
     * @return IHGProperty
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
     * Set url
     *
     * @param string $url
     * @return IHGProperty
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
     * Set expires
     *
     * @param \DateTime $expires
     * @return IHGProperty
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return IHGProperty
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
     * @return IHGProperty
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
     * Set brand
     *
     * @param Brand $brand
     * @return IHGProperty
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

        $this->updateRatePretty();
    }

    /*
     * Slugify
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

    public function generateRatePretty()
    {

        $prettyString = '$0.00';
        $rateValue = $this->getRate();

        setlocale(LC_MONETARY, 'en_US.UTF-8');
        if ($rateValue != null) {
            $prettyString = money_format('%.2n', $rateValue);
        }
        return $prettyString;

    }

    public function formatPhone($phone)
    {
        $cleanPhone = '';
        $phone = str_replace('-', '', $phone);
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(')', '', $phone);
        $phone = str_replace('x', '', $phone);
        $phone = str_replace('.', '', $phone);


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

        if ($cleanPhone) {
            return $cleanPhone;
        } else {
            return null;
        }
    }
}
