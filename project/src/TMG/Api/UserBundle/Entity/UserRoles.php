<?php

namespace TMG\Api\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserRoles
 *
 * @ORM\Table(name="UserRoles")
 * @ORM\Entity(repositoryClass="TMG\Api\UserBundle\Entity\Repository\UserRolesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserRoles
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
     * @ORM\Column(name="role", unique=true, type="string", length=255)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="platform", type="string", length=255)
     */
    private $platform;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="UserRights", inversedBy="roles", cascade={"persist"})
     * @ORM\JoinTable(name="RoleRights",
     * joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="right_id", referencedColumnName="id")}
     * )
     */
    private $rights;

    /**
     * (construct)
     */
    public function __construct()
    {
        $this->rights = new ArrayCollection();
    }

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
     * Set role
     *
     * @param string $role
     * @return UserRoles
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set platform
     *
     * @param string $platform
     * @return UserRoles
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return UserRoles
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserRoles
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

    /**
     * Add rights
     *
     * @param \TMG\Api\UserBundle\Entity\UserRights $rights
     * @return UserRoles
     */
    public function addRight(\TMG\Api\UserBundle\Entity\UserRights $rights)
    {
        $this->rights[] = $rights;

        return $this;
    }

    /**
     * Remove rights
     *
     * @param \TMG\Api\UserBundle\Entity\UserRights $rights
     */
    public function removeRight(\TMG\Api\UserBundle\Entity\UserRights $rights)
    {
        $this->rights->removeElement($rights);
    }

    /**
     * Get rights
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @param ArrayCollection $rights
     */
    public function setRights(ArrayCollection $rights)
    {
        $this->rights = $rights;
    }
}
