<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class AxBook extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $code;

    /**
     * @ORM\ManyToMany(targetEntity="AxIssue", mappedBy="books")
     */
    protected $issues;

    /**
     * @ORM\OneToMany(targetEntity="AxContract", mappedBy="book", cascade={"persist"})
     */
    protected $contracts;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * AxBook constructor.
     */
    public function __construct()
    {
        $this->issues = new ArrayCollection();
        $this->contracts = new ArrayCollection();
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
     * @return AxBook
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
     * @return AxBook
     */
    public function setName($v)
    {
        $this->name = $v;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $v
     *
     * @return AxBook
     */
    public function setCode($v)
    {
        $this->code = $v;
        return $this;
    }

    /**
     * @return arrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @param arrayCollection $v
     *
     * @return AxBook
     */
    public function setIssues($v)
    {
        $this->issues = $v;
        return $this;
    }

    /**
     * @return arrayCollection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * @param AxContract $v
     *
     * @return AxBook
     */
    public function addContract(AxContract $v)
    {
        $this->contracts->add($v);
        return $this;
    }

    /**
     * @param arrayCollection $v
     *
     * @return AxBook
     */
    public function setContracts(ArrayCollection $v)
    {
        $this->contracts = $v;
        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return AxBook
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
}
