<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="oauth_users", indexes={@ORM\Index(columns={"realm", "username"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class OAuthUser extends AbstractEntity
{
    const PASSWORD_ALG = PASSWORD_DEFAULT;

    /**
     * @ORM\OneToOne(targetEntity="User", cascade={"persist"})
     * @ORM\Id
     */
    protected $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $scope;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $realm;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $tutorial;

    /**
     * @ORM\OneToMany(targetEntity="OAuthAuthorizationCode", mappedBy="user")
     */
    protected $authorizationCodes;

    /**
     * @ORM\OneToMany(targetEntity="OAuthAccessToken", mappedBy="user")
     */
    protected $accessTokens;

    /**
     * @ORM\OneToMany(targetEntity="OAuthRefreshToken", mappedBy="user")
     */
    protected $refreshTokens;

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
     * @var \DateTime
     *
     * @ORM\Column(name="migrated_at", type="datetime", nullable=true)
     */
    private $migratedAt;

    /**
     * OAuthUser constructor
     */
    public function __construct()
    {
        $this->accessTokens = new ArrayCollection;
        $this->authorizationCodes = new ArrayCollection;
        $this->refreshTokens = new ArrayCollection;
    }

    /**
     * @return User
     */
    public function getId()
    {
        return $this->getUser();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return OAuthUser
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return OAuthUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password to hashed version of $password
     *
     * @param string $password
     *
     * @return OAuthUser
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, self::PASSWORD_ALG);

        return $this;
    }

    /**
     * Set password to exactly what input is
     *
     * @param string $password
     *
     * @return OAuthUser
     */
    public function setRawPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Checks password against current password, and re-hash if necessary.
     *
     * @param string $password
     *
     * @return bool was the provided password correct?
     */
    public function verifyAndRehashPassword($password)
    {
        if (!static::verifyPassword($password, $this->password)) {
            return false;
        }

        if (password_needs_rehash($this->password, self::PASSWORD_ALG)) {
            $this->setPassword($password);
        }

        return true;
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public static function verifyPassword($password, $hash)
    {
        if (strpos($hash, '$old$') !== 0) {
            return password_verify($password, $hash);
        }

        $pieces = explode('$', $hash, 4);
        $salt = $pieces[2];
        $hash = $pieces[3];

        return md5($password . $salt) === $hash;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return OAuthUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return OAuthUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

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
     * @param string $scope
     *
     * @return OAuthUser
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

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
     * @param string $realm
     *
     * @return OAuthUser
     */
    public function setRealm($realm)
    {
        $this->realm = $realm;

        return $this;
    }

    /**
     * @return bool
     */
    public function getTutorial()
    {
        return $this->tutorial;
    }

    /**
     * @param bool $tutorial
     *
     * @return OAuthUser
     */
    public function setTutorial($tutorial)
    {
        $this->tutorial = $tutorial;

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
     * @param ArrayCollection $authorizationCodes
     *
     * @return OAuthUser
     */
    public function setAuthorizationCodes(ArrayCollection $authorizationCodes)
    {
        $this->authorizationCodes = $authorizationCodes;

        return $this;
    }

    /**
     * @param OAuthAuthorizationCode $authorizationCode
     *
     * @return OAuthUser
     */
    public function addAuthorizationCode(OAuthAuthorizationCode $authorizationCode)
    {
        $this->authorizationCodes->add($authorizationCode);

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
     * @param ArrayCollection $accessTokens
     *
     * @return OAuthUser
     */
    public function setAccessTokens(ArrayCollection $accessTokens)
    {
        $this->accessTokens = $accessTokens;

        return $this;
    }

    /**
     * @param OAuthAccessToken $accessToken
     *
     * @return OAuthUser
     */
    public function addAccessToken(OAuthAccessToken $accessToken)
    {
        $this->accessTokens->add($accessToken);

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
     * @param ArrayCollection $refreshTokens
     *
     * @return OAuthUser
     */
    public function setRefreshTokens(ArrayCollection $refreshTokens)
    {
        $this->refreshTokens = $refreshTokens;
        return $this;
    }

    /**
     * @param OAuthRefreshToken $refreshToken
     *
     * @return OAuthUser
     */
    public function addRefreshToken(OAuthRefreshToken $refreshToken)
    {
        $this->refreshTokens->add($refreshToken);
        $refreshToken->setUser($this);
        return $this;
    }

    /**
     * Checks password against legacy password.
     *
     * @param $password
     *
     * @return bool was the provided password correct.
     */
    public function verifyForLegacyPassword($password)
    {
        $old_password =  md5($password.$this->username."eawofij123");

        if ($this->password == $old_password) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return OAuthUser
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
     * @return OAuthUser
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
     * Set migratedAt
     *
     * @param \DateTime $migratedAt
     *
     * @return OAuthUser
     */
    public function setMigratedAt(\DateTime $migratedAt)
    {
        $this->migratedAt = $migratedAt;

        return $this;
    }

    /**
     * Get migratedAt
     *
     * @return \DateTime
     */
    public function getMigratedAt()
    {
        return $this->migratedAt;
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
