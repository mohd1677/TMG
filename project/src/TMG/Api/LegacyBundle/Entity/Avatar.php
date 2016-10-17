<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Used to track user icons
 *
 * @ORM\Entity
 */
class Avatar extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * URL to the avatar image.
     *
     * @ORM\Column(type="string", length=120, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "120",
     *      minMessage = "Avatar location url must be at least {{ limit }} characters.",
     *      maxMessage = "Avatar Location url cannot be longer than {{ limit }} characters."
     * )
     */
    protected $avatar;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="avatar")
     *
     * @Assert\Valid
     */
    protected $user;

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
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $value
     *
     * @return Avatar
     */
    public function setId($value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return Avatar
     */
    public function setAvatar($value)
    {
        $this->avatar = $value;

        return $this;
    }

    /**
     * @param User $value
     *
     * @return Avatar
     */
    public function setUser($value)
    {
        $this->user = $value;

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
     * @return Avatar
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
     * @return Avatar
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
