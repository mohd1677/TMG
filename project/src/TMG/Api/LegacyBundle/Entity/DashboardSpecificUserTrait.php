<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait DashboardSpecificUserTrait
{
    /**
     * Role of the user. Unused for HotelCoupons
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "20",
     *      minMessage = "Your Role must be at least {{ limit }} characters.",
     *      maxMessage = "Your Role name cannot be longer than {{ limit }} characters."
     * )
     */
    protected $role;

    /**
     * @ORM\OneToMany(targetEntity="CmsDestination", mappedBy="author", cascade={"persist"})
     *
     * @Assert\Valid
     */
    protected $destinations;

    /**
     * @ORM\OneToMany(targetEntity="Announcement", mappedBy="author", cascade={"persist"})
     *
     * @Assert\Valid
     */
    protected $announcements;

    /**
     * @ORM\ManyToMany(targetEntity="Property", inversedBy="users", cascade={"persist"})
     *
     * @Assert\Valid
     */
    protected $properties;

    /**
     * @ORM\ManyToMany(targetEntity="Right", cascade={"persist"})
     *
     * @Assert\Valid
     */
    protected $rights;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $confirmationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * Dashboard constructor
     */
    public function dashboardConstructor()
    {
        $this->announcements = new ArrayCollection();
        $this->destinations = new ArrayCollection();
        $this->properties = new ArrayCollection();
        $this->rights = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $v
     *
     * @return DashboardSpecificUserTrait
     */
    public function setRole($v)
    {
        $this->role = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDestinations()
    {
        return $this->destinations;
    }

    /**
     * @param ArrayCollection $v
     * @return DashboardSpecificUserTrait
     */
    public function setDestinations($v)
    {
        $this->destinations = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAnnouncements()
    {
        return $this->announcements;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return DashboardSpecificUserTrait
     */
    public function setAnnouncements($v)
    {
        $this->announcements = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return DashboardSpecificUserTrait
     */
    public function setProperties(ArrayCollection $v)
    {
        $this->properties = $v;

        return $this;
    }

    /**
     * @param Property $p
     *
     * @return DashboardSpecificUserTrait
     */
    public function addProperty(Property $p)
    {
        $this->properties->add($p);

        return $this;
    }

    /**
     * @param Property $p
     *
     * @return DashboardSpecificUserTrait
     */
    public function removeProperty($p)
    {
        $this->properties->removeElement($p);

        return $this;
    }

    /**
     * @param Right $v
     */
    public function setRights(Right $v)
    {
        $this->rights[] = $v;
    }

    /**
     * @return ArrayCollection
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param string $v
     *
     * @return DashboardSpecificUserTrait
     */
    public function setConfirmationToken($v)
    {
        $this->confirmationToken = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param \DateTime|null $v
     *
     * @return DashboardSpecificUserTrait
     */
    public function setPasswordRequestedAt(\DateTime $v = null)
    {
        $this->passwordRequestedAt = $v;

        return $this;
    }

    /**
     * @param int $ttl
     *
     * @return bool
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
               $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }
}
