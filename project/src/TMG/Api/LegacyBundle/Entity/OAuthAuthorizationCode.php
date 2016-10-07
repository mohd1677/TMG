<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="oauth_authorization_codes")
 * @ORM\Entity
 */
class OAuthAuthorizationCode extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=40, nullable=false)
     * @ORM\Id
     */
    protected $authorizationCode;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $redirectUri;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $expires;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $scope;

    /**
     * @ORM\ManyToOne(targetEntity="OAuthClient", inversedBy="authorizationCodes", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="client_id")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="OAuthUser", inversedBy="authorizationCodes", cascade={"persist"})
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
        return $this->authorizationCode;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param string $v
     *
     * @return OAuthAuthorizationCode
     */
    public function setAuthorizationCode($v)
    {
        $this->authorizationCode = $v;

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
     * @return OAuthAuthorizationCode
     */
    public function setRedirectUri($v)
    {
        $this->redirectUri = $v;

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
     * @param \DateTime $v
     *
     * @return OAuthAuthorizationCode
     */
    public function setExpires(\DateTime $v)
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
     * @return OAuthAuthorizationCode
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
     * @return OAuthAuthorizationCode
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
     * @param OAuthUser $v
     *
     * @return OAuthAuthorizationCode
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
     * @return OAuthAuthorizationCode
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
     * @return OAuthAuthorizationCode
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
