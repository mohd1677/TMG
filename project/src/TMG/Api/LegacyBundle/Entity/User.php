<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Users for either HotelCouopons.com or MyTMG
 *
 * To update the users password, simply send a `password` field with
 * a normal update. Even though password isn't shown here, it will be
 * changed.
 *
 * @ORM\Entity
 * @ORM\Table(name="users",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="user_unique",columns={"email", "realm"})}
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class User extends AbstractEntity
{
    use HotelCouponsSpecificUserTrait;
    use DashboardSpecificUserTrait;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

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
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = false
     * )
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * The realm the user is for. Either "hotelcoupons" or "dashboard"
     * @ORM\Column(type="string")
     */
    protected $realm;

    /**
     * @ORM\OneToOne(targetEntity="Avatar", mappedBy="user", cascade={"persist"})
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $province;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $postalCode;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->hotelCouponsConstructor();
        $this->dashboardConstructor();
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
     * @return User
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
     * @param string $phone
     *
     * @return null|string
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

        if ($cleanPhone) {
            return $cleanPhone;
        } else {
            return null;
        }
    }

    /**
     * @return User
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     *
     * @return User
     */
    public function setId($v)
    {
        $this->id = $v;

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
     * @return User
     */
    public function setEmail($v)
    {
        $this->email = $v;

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
     * @return User
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRealm()
    {
        return $this->realm;
    }

    /**
     * @param string $v
     *
     * @return User
     */
    public function setRealm($v)
    {
        $this->realm = $v;

        return $this;
    }

    /**
     * @return Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param Avatar $v
     *
     * @return User
     */
    public function setAvatar(Avatar $v)
    {
        $this->avatar = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $v
     *
     * @return User
     */
    public function setAddress($v)
    {
        $this->address = $v;

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
     * @return User
     */
    public function setCity($v)
    {
        $this->city = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $v
     *
     * @return User
     */
    public function setProvince($v)
    {
        $this->province = $v;

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
     * @return User
     */
    public function setCountry($v)
    {
        $this->country = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $v
     *
     * @return User
     */
    public function setPostalCode($v)
    {
        $this->postalCode = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
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
}
