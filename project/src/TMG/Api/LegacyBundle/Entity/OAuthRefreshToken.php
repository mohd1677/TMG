<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="oauth_refresh_tokens")
 * @ORM\Entity
 */
class OAuthRefreshToken extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=40, nullable=false)
     * @ORM\Id
     */
    protected $refreshToken = '';

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $expires;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $scope;

    /**
     * @ORM\ManyToOne(targetEntity="OAuthClient", inversedBy="refreshTokens", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="client_id")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="OAuthUser", inversedBy="refreshTokens", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="user_id")
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
     * @return string
     */
    public function getId()
    {
        return $this->refreshToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $v
     *
     * @return OAuthRefreshToken
     */
    public function setRefreshToken($v)
    {
        $this->refreshToken = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param DateTime $v
     *
     * @return OAuthRefreshToken
     */
    public function setExpires(DateTime $v)
    {
        $this->expires = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $v
     *
     * @return OAuthRefreshToken
     */
    public function setScope($v)
    {
        $this->scope = $v;

        return $this;
    }

    /**
     * @return OAuthClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param OAuthClient $v
     *
     * @return OAuthRefreshToken
     */
    public function setClient(OAuthClient $v)
    {
        $this->client = $v;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param OAuthUser $v
     *
     * @return OAuthRefreshToken
     */
    public function setUser(OAuthUser $v)
    {
        $this->user = $v;

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
     * @return OAuthRefreshToken
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
     * @return OAuthRefreshToken
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
