<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\AddressRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Address extends AbstractEntity
{
    use LatitudeLongitudeTrait;

    public static $states = [
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'DC' => 'District Of Columbia',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',

        // non-states
        'AA' => 'U.S. Armed Forces - Americas',
        'AE' => 'U.S. Armed Forces - Europe',
        'AP' => 'U.S. Armed Forces - Pacific',
        'AS' => 'American Samoa',
        'FM' => 'Federated States of Micronesia',
        'GU' => 'Guam',
        'MH' => 'Marshall Islands',
        'MP' => 'Northern Mariana Islands',
        'PW' => 'Palau',
        'PR' => 'Puerto Rico',
        'VI' => 'Virgin Islands',
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose
     */
    protected $line1;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Serializer\Expose
     */
    protected $line2;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose
     */
    protected $city;

    /**
     * Two letter state code
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose
     */
    protected $state;

    /**
     * US Zip, ZIP+4, or Canadian Zip
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose
     */
    protected $zip;

    /**
     * Two letter country code
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose
     */
    protected $country;

    /**
     * md5 of line1, line2, city, state, zip, and country
     * For internal use.
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $hash;

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
     * @return Address
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * @param string $v
     *
     * @return Address
     */
    public function setLine1($v)
    {
        $this->line1 = $v;

        if ($this->hasHash()) {
            $this->updateHash();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * @param string $v
     *
     * @return Address
     */
    public function setLine2($v)
    {
        $this->line2 = $v;

        if ($this->hasHash()) {
            $this->updateHash();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $v
     *
     * @return Address
     */
    public function setCity($v)
    {
        $this->city = $v;

        if ($this->hasHash()) {
            $this->updateHash();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $v
     *
     * @return Address
     */
    public function setState($v)
    {
        $v = strtoupper($v);
        $this->state = $v;

        if ($this->hasHash()) {
            $this->updateHash();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getStateName()
    {
        return self::stateNameFromCode($this->state);
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $v
     *
     * @return Address
     */
    public function setZip($v)
    {
        $this->zip = $v;

        if ($this->hasHash()) {
            $this->updateHash();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $v
     *
     * @return Address
     */
    public function setCountry($v)
    {
        $this->country = $v;

        if ($this->hasHash()) {
            $this->updateHash();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return bool
     */
    public function hasHash()
    {
        return isset($this->hash);
    }

    /**
     * @return string
     */
    public function updateHash()
    {
        return $this->hash = $this->generateHash();
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
     * @return Address
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
     * @return Address
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

    /**
     * @return string
     */
    public function generateHash()
    {
        return md5(implode(",", [
            $this->line1,
            $this->line2,
            $this->city,
            $this->state,
            $this->zip,
            $this->country
        ]));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->country == 'US') {
            return sprintf(
                "%s%s %s, %s %s",
                ucwords($this->line1),
                ucwords($this->line2 ? ' ' . $this->line2 : ''),
                ucwords($this->city),
                strtoupper($this->state),
                $this->zip
            );
        } else {
            return sprintf(
                "%s%s %s, %s %s %s",
                ucwords($this->line1),
                ucwords($this->line2 ? ' ' . $this->line2 : ''),
                ucwords($this->city),
                strtoupper($this->state),
                $this->zip,
                strtoupper($this->country)
            );
        }
    }

    /**
     * @Assert\True(message = "The zip code is invalid")
     *
     * @return bool
     */
    public function isZipCode()
    {
        // Validates that a zip code is USA or Canada valid format.
        $pattern = "/(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$)/";

        return (bool)preg_match($pattern, $this->zip);
    }

    /**
     * @Assert\True(message = "The country code is invalid")
     *
     * @return bool
     */
    public function isValidCountry()
    {
        static $countries = array('MX', 'CA', 'US');

        return in_array(strtoupper($this->country), $countries, true);
    }

    /**
     * @Assert\True(message = "The state is invalid")
     *
     * @return bool
     */
    public function isStateCode($code = null)
    {
        $code = strtoupper($code ?: $this->state);

        return isset(self::$states[$code]);
    }

    /**
     * @return string|null
     */
    public static function stateNameFromCode($code)
    {
        $code = strtoupper($code);

        return isset(self::$states[$code]) ? self::$states[$code] : null;
    }
}
