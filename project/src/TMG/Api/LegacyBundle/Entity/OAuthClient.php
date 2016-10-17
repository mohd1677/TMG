<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="oauth_clients")
 * @ORM\Entity
 */
class OAuthClient extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=80, nullable=false)
     * @ORM\Id
     */
    protected $clientId = '';

    /**
     * @ORM\Column(type="string", length=80, nullable=false)
     */
    protected $clientSecret;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $redirectUri;

    /**
     * @ORM\Column(type="string", length=80, nullable=false)
     */
    protected $realm;

    /**
     * @ORM\OneToMany(targetEntity="OAuthAccessToken", mappedBy="client")
     */
    protected $accessTokens;

    /**
     * @ORM\OneToMany(targetEntity="OAuthAuthorizationCode", mappedBy="client")
     */
    protected $authorizationCodes;

    /**
     * @ORM\OneToMany(targetEntity="OAuthRefreshToken", mappedBy="client")
     */
    protected $refreshTokens;

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
     * OAuthClient constructor
     */
    public function __construct()
    {
        $this->accessTokens = new ArrayCollection;
        $this->authorizationCodes = new ArrayCollection;
        $this->refreshTokens = new ArrayCollection;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getClientId();
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $v
     *
     * @return OAuthClient
     */
    public function setClientId($v)
    {
        $this->clientId = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $v
     *
     * @return OAuthClient
     */
    public function setClientSecret($v)
    {
        $this->clientSecret = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $v
     *
     * @return OAuthClient
     */
    public function setRedirectUri($v)
    {
        $this->redirectUri = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRealm()
    {
        return $this->realm;
    }

    /**
     * @param string $v
     *
     * @return OAuthClient
     */
    public function setRealm($v)
    {
        $this->realm = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return OAuthClient
     */
    public function setAccessTokens(ArrayCollection $v)
    {
        $this->accessTokens = $v;

        return $this;
    }

    /**
     * @param OAuthAccessToken $v
     *
     * @return OAuthClient
     */
    public function addAccessToken(OAuthAccessToken $v)
    {
        $this->accessTokens->add($v);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthorizationCodes()
    {
        return $this->authorizationCodes;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return OAuthClient
     */
    public function setAuthorizationCodes(ArrayCollection $v)
    {
        $this->authorizationCodes = $v;

        return $this;
    }

    /**
     * @param OAuthAuthorizationCode $v
     *
     * @return OAuthClient
     */
    public function addAuthorizationCode(OAuthAuthorizationCode $v)
    {
        $this->authorizationCodes->add($v);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRefreshTokens()
    {
        return $this->refreshTokens;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return OAuthClient
     */
    public function setRefreshTokens(ArrayCollection $v)
    {
        $this->refreshTokens = $v;

        return $this;
    }

    /**
     * @param OAuthRefreshToken $v
     *
     * @return OAuthClient
     */
    public function addRefreshToken(OAuthRefreshToken $v)
    {
        $this->refreshTokens->add($v);
        $v->setClient($this);

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
     * @return OAuthClient
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
     * @return OAuthClient
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
