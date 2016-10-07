<?php

namespace TMG\Api\DocsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiDocMeta
 *
 * @ORM\Table(name="ApiDocMeta")
 * @ORM\Entity(repositoryClass="TMG\Api\DocsBundle\Entity\Repository\ApiDocMetaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ApiDocMeta
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $routeUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="\TMG\Api\DocsBundle\Entity\DocParams", mappedBy="route", cascade="persist")
     */
    private $params;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $instructions;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Timestamp of last update
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->params = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set route
     *
     * @param string $route
     * @return ApiDocMeta
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set routeUrl
     *
     * @param string $routeUrl
     * @return ApiDocMeta
     */
    public function setRouteUrl($routeUrl)
    {
        $this->routeUrl = $routeUrl;

        return $this;
    }

    /**
     * Get routeUrl
     *
     * @return string
     */
    public function getRouteUrl()
    {
        return $this->routeUrl;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ApiDocMeta
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
     * Set instructions
     *
     * @param string $instructions
     * @return ApiDocMeta
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions
     *
     * @return string
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return ApiDocMeta
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return ApiDocMeta
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ApiDocMeta
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
     * @return ApiDocMeta
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
        $this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Add params
     *
     * @param \TMG\Api\DocsBundle\Entity\DocParams $params
     * @return ApiDocMeta
     */
    public function addParam(\TMG\Api\DocsBundle\Entity\DocParams $params)
    {
        $params->setRoute($this);
        $this->params[] = $params;

        return $this;
    }

    /**
     * Remove params
     *
     * @param \TMG\Api\DocsBundle\Entity\DocParams $params
     */
    public function removeParam(\TMG\Api\DocsBundle\Entity\DocParams $params)
    {
        $this->params->removeElement($params);
    }

    /**
     * Get params
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParams()
    {
        return $this->params;
    }
}
