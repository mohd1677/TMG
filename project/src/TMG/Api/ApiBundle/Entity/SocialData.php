<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SocialData
 *
 * @ORM\Table(name="SocialData")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\SocialDataRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SocialData
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
     * @ORM\ManyToOne(targetEntity="Social", inversedBy="socialData")
     * @ORM\JoinColumn(name="social_id", referencedColumnName="id")
     **/
    private $social;

    /**
     * @var string
     *
     * @ORM\Column(name="yrmo", type="integer", length=4)
     */
    private $yrmo;

    /**
     * @var type
     *
     * @ORM\ManyToOne(targetEntity="SocialDataType", cascade={"persist"})
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="fans", type="integer")
     */
    private $fans;

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
     * Set social
     *
     * @param Social $social
     * @return SocialData
     */
    public function setSocial(Social $social)
    {
        $this->social = $social;

        return $this;
    }

    /**
     * Get social
     *
     * @return Social
     */
    public function getSocial()
    {
        return $this->social;
    }

    /**
     * Set yrmo
     *
     * @param integer $yrmo
     * @return SocialData
     */
    public function setYrmo($yrmo)
    {
        $this->yrmo = $this->formatYRMO($yrmo);

        return $this;
    }

    /**
     * Get yrmo
     *
     * @return integer
     */
    public function getYrmo()
    {
        return $this->yrmo;
    }

    /**
     * Set type
     *
     * @param SocialDataType $type
     * @return SocialData
     */
    public function setType(SocialDataType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return SocialDataType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set fans
     *
     * @param integer $fans
     * @return SocialData
     */
    public function setFans($fans)
    {
        $this->fans = $fans;

        return $this;
    }

    /**
     * Get fans
     *
     * @return integer
     */
    public function getFans()
    {
        return $this->fans;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SocialData
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
     * @return SocialData
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


    /**
     * Format YRMO
     *
     * @return integer
     */
    public function formatYRMO($date)
    {
        if ($date instanceof \DateTime) {
            $date = $date->format('ym');
            return (int) $date;
        } else {
            $date = new \DateTime($date);
            $date = $date->format('ym');
            return (int) $date;
        }
    }
}
