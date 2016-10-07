<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Used when finding nearby cities
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="TMG\Api\LegacyBundle\Entity\Repository\CityCenterRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CityCenter extends AbstractEntity
{
    use LatitudeLongitudeTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $heroImageUrl;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     *
     * @return CityCenter
     */
    public function setId($v)
    {
        $this->id = $v;

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
     * @return CityCenter
     */
    public function setCity($v)
    {
        $this->city = $v;

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
     * @return CityCenter
     */
    public function setState($v)
    {
        $this->state = $v;

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
     * @return CityCenter
     */
    public function setCountry($v)
    {
        $this->country = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeroImageUrl()
    {
        return $this->heroImageUrl;
    }

    /**
     * @param string $v
     *
     * @return CityCenter
     */
    public function setHeroImageUrl($v)
    {
        $this->heroImageUrl = $v;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CityCenter
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return CityCenter
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
