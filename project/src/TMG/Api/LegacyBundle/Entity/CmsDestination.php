<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class CmsDestination extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $metaKeywords;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $metaDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isPublished;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isPublishedHome;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isPublishedDestinations;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isFeatured;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $hero;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="destinations")
     * @Assert\Valid
     */
    protected $author;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title2;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title3;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content3;

    /**
     * @ORM\ManyToMany(targetEntity="CmsWidget", cascade={"persist"})
     */
    protected $sideWidgets;

    /**
    * @ORM\OneToOne(targetEntity="CmsImage", cascade={"persist"})
    * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
    */
    protected $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $summary;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * CmsDestination constructor.
     */
    public function __construct()
    {
        $this->sideWidgets = new ArrayCollection;
    }

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
     * @return CmsDestination
     */
    public function setId($v)
    {
        $this->id = $v;

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
     * @return CmsDestination
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setTitle($v)
    {
        $this->title = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setMetaKeywords($v)
    {
        $this->metaKeywords = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setMetaDescription($v)
    {
        $this->metaDescription = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setContent($v)
    {
        $this->content = $v;

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
     * @return CmsDestination
     */
    public function setSlug($v)
    {
        $this->slug = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param bool $v
     *
     * @return CmsDestination
     */
    public function setIsPublished($v)
    {
        $this->isPublished = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPublishedHome()
    {
        return $this->isPublishedHome;
    }

    /**
     * @param bool $v
     *
     * @return CmsDestination
     */
    public function setIsPublishedHome($v)
    {
        $this->isPublishedHome = $v;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPublishedDestinations()
    {
        return $this->isPublishedDestinations;
    }

    /**
     * @param bool $v
     *
     * @return CmsDestination
     */
    public function setIsPublishedDestinations($v)
    {
        $this->isPublishedDestinations = $v;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * @param bool $v
     *
     * @return CmsDestination
     */
    public function setIsFeatured($v)
    {
        $this->isFeatured = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getHero()
    {
        return $this->hero;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setHero($v)
    {
        $this->hero = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $value
     *
     * @return CmsDestination
     */
    public function setAuthor(User $value)
    {
        $this->author = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle1()
    {
        return $this->title1;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setTitle1($v)
    {
        $this->title1 = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle2()
    {
        return $this->title2;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setTitle2($v)
    {
        $this->title2 = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle3()
    {
        return $this->title3;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setTitle3($v)
    {
        $this->title3 = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent1()
    {
        return $this->content1;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setContent1($v)
    {
        $this->content1 = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent2()
    {
        return $this->content2;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setContent2($v)
    {
        $this->content2 = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent3()
    {
        return $this->content3;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setContent3($v)
    {
        $this->content3 = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSideWidgets()
    {
        return $this->sideWidgets;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return CmsDestination
     */
    public function setSideWidgets($v)
    {
        $this->sideWidgets = $v;

        return $this;
    }

    /**
     * @param CmsWidget $v
     *
     * @return CmsDestination
     */
    public function addSideWidget(CmsWidget $v)
    {
        $this->sideWidgets[] = $v;

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
     * @param string $value
     *
     * @return CmsDestination
     */
    public function setImage($value)
    {
        $this->image = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $v
     *
     * @return CmsDestination
     */
    public function setSummary($v)
    {
        $this->summary = $v;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CmsDestination
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     * @return CmsDestination
     */
    public function setUpdatedAt(\DateTime $updatedAt)
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
}
