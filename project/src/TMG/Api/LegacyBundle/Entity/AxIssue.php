<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class AxIssue extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AxItem", inversedBy="issues", cascade={"persist"})
     * @ORM\JoinColumn(name="item_number_id", referencedColumnName="id", nullable=true)
     */
    protected $itemNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $itemCode;

    /**
     * @ORM\ManyToMany(targetEntity="AxBook", inversedBy="issues")
     * @ORM\JoinTable(name="book_issues",
     *      joinColumns={@ORM\joinColumn(name="issue_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")}
     * )
     */
    protected $books;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $spaceReserved;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $adSize;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $color;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $position;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $issueNumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $confirmed;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $emailCopy;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $faxCopy;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $eightHundredNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $category;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $updatedBy;

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
     * AxIssue constructor.
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
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
     * @return AxIssue
     */
    public function setId($v)
    {
        $this->id = $v;
        return $this;
    }

    /**
     * @return AxItem
     */
    public function getItemNumber()
    {
        return $this->itemNumber;
    }

    /**
     * @param AxItem $v
     *
     * @return AxIssue
     */
    public function setItemNumber(AxItem $v)
    {
        $this->itemNumber = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemCode()
    {
        return $this->itemCode;
    }

    /**
     * @param string $v
     *
     * @return AxIssue
     */
    public function setItemCode($v)
    {
        $this->itemCode = $v;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return AxIssue
     */
    public function setBooks(ArrayCollection $v)
    {
        $this->books = $v;
        return $this;
    }

    /**
     * @param AxBook $v
     *
     * @return AxIssue
     */
    public function addBook(AxBook $v)
    {
        $this->books[] = $v;
        return $this;
    }

    /**
     * @param AxBook $v
     *
     * @return ArrayCollection
     */
    public function removeBook($v)
    {
        return $this->books->removeElement($v);
    }

    /**
     * @return string
     */
    public function getSpaceReserved()
    {
        return $this->spaceReserved;
    }

    /**
     * @param string $v
     *
     * @return AxIssue
     */
    public function setSpaceReserved($v)
    {
        $this->spaceReserved = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdSize()
    {
        return $this->adSize;
    }

    /**
     * @param string $v
     *
     * @return AxIssue
     */
    public function setAdSize($v)
    {
        $this->adSize = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $v
     *
     * @return AxIssue
     */
    public function setColor($v)
    {
        $this->color = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $v
     *
     * @return AxIssue
     */
    public function setPosition($v)
    {
        $this->position = $v;
        return $this;
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
     * @return AxIssue
     */
    public function setDescription($v)
    {
        $this->description = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getIssueNumber()
    {
        return $this->issueNumber;
    }

    /**
     * @param string $v
     *
     * @return AxIssue
     */
    public function setIssueNumber($v)
    {
        $this->issueNumber = $v;
        return $this;
    }

    /**
     * @return bool
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param bool $v
     *
     * @return AxIssue
     */
    public function setConfirmed($v)
    {
        $this->confirmed = $v;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEmailCopy()
    {
        return $this->emailCopy;
    }

    /**
     * @param bool $v
     *
     * @return AxIssue
     */
    public function setEmailCopy($v)
    {
        $this->emailCopy = $v;
        return $this;
    }

    /**
     * @return bool
     */
    public function getFaxCopy()
    {
        return $this->faxCopy;
    }

    /**
     * @param bool $v
     *
     * @return AxIssue
     */
    public function setFaxCopy($v)
    {
        $this->faxCopy = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getEightHundredNumber()
    {
        return $this->eightHundredNumber;
    }

    /**
     * @param string $v
     *
     * @return AxIssue
     */
    public function setEightHundredNumber($v)
    {
        $this->eightHundredNumber = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $v
     *
     * @return AxIssue
     */
    public function setCategory($v)
    {
        $this->category = $v;
        return $this;
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
     * @return AxIssue
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
     * @return AxIssue
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
}
