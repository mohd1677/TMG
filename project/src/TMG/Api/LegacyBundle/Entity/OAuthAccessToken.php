<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="oauth_access_tokens")
 * @ORM\Entity
 */
class OAuthAccessToken extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=40, nullable=false)
     * @ORM\Id
     */
    protected $accessToken;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $expires;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $scope;

    /**
     * @ORM\ManyToOne(targetEntity="OAuthClient", inversedBy="accessTokens", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="client_id")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="OAuthUser", inversedBy="accessTokens", cascade={"persist"})
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
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $v
     *
     * @return OAuthAccessToken
     */
    public function setAccessToken($v)
    {
        $this->accessToken = $v;

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
     * @return OAuthAccessToken
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
     * @return OAuthAccessToken
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
     * @return OAuthAccessToken
     */
    public function setClient(OAuthClient $v)
    {
        $this->client = $v;

        return $this;
    }

    /**
     * @return OAuthUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param OAuthUser|null $v
     *
     * @return OAuthAccessToken
     */
    public function setUser(OAuthUser $v = null)
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
     * @return OAuthAccessToken
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
     * @return OAuthAccessToken
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
