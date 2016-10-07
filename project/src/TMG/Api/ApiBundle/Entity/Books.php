<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Books
 *
 * @ORM\Table(name="Books")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\BooksRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Books
{
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->contracts = new ArrayCollection();
    }

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=56)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=255, nullable=true)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="newsletter_name", type="string", length=255, nullable=true)
     */
    private $newsletterName;

    /**
     * @ORM\ManyToMany(targetEntity="TMG\Api\UserBundle\Entity\User", mappedBy="books")
     */
    protected $users;

     /**
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="book")
     **/
    private $contracts;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Books
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set code
     *
     * @param string $code
     * @return Books
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Books
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set newsletterName
     *
     * @param string $newsletterName
     * @return Books
     */
    public function setNewsletterName($newsletterName)
    {
        $this->newsletterName = $newsletterName;

        return $this;
    }

    /**
     * Get newsletterName
     *
     * @return string
     */
    public function getNewsletterName()
    {
        return $this->newsletterName;
    }

    /**
     * Get users
     *
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add user
     *
     * @return Books
     */
    public function addUser($v)
    {
        $this->users->add($v);
        return $this;
    }

    /**
     * Set users
     *
     * @return Books
     */
    public function setUsers(ArrayCollection $v)
    {
        $this->users = $v;
        return $this;
    }

    /**
     * Set contracts
     *
     * @param string $contracts
     * @return Books
     */
    public function setContracts(ArrayCollection $contracts)
    {
        $this->contracts = $contracts;

        return $this;
    }

    /**
     * Get contracts
     *
     * @return string
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Add Contract
     *
     * @param string $contracts
     * @return Books
     */
    public function addContract(Contract $v)
    {
        $this->contracts[] = $v;
        return $this;
    }

    /**
     * Remove Contract
     *
     * @param string $contracts
     * @return Books
     */
    public function removeContract(Contract $v)
    {
        $this->contracts->removeElement($v);
        return $this;
    }

    /**
     * Has Contract
     *
     * @param string $contracts
     * @return boolean
     */
    public function hasContract(Contract $v)
    {
        return $this->contracts->contains($v);
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Books
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
     * @return Books
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
