<?php

namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CmsWidget extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\ManyToMany(targetEntity="CmsBlock", mappedBy="widgets", cascade={"persist"})
     */
    protected $blocks;

    /**
     * @ORM\OneToMany(targetEntity="CmsField", mappedBy="widget", cascade={"persist"})
     */
    protected $fields;

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
     * CmsWidget constructor.
     */
    public function __construct()
    {
        $this->fields = new ArrayCollection;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $v
     *
     * @return CmsWidget
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $v
     *
     * @return CmsWidget
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $v
     *
     * @return CmsWidget
     */
    public function setType($v)
    {
        $this->type = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $v
     *
     * @return CmsWidget
     */
    public function setSlug($v)
    {
        $this->slug = $v;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return CmsWidget
     */
    public function setBlocks(ArrayCollection $v)
    {
        $this->blocks = $v;

        return $this;
    }

    /**
     * @param CmsBlock $b
     *
     * @return CmsWidget
     */
    public function addBlock(CmsBlock $b)
    {
        $this->blocks[] = $b;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param ArrayCollection $v
     *
     * @return CmsWidget
     */
    public function setFields(ArrayCollection $v)
    {
        $this->fields = $v;

        return $this;
    }

    /**
     * @param CmsField $field
     *
     * @return CmsWidget
     */
    public function addField(CmsField $field)
    {
        $this->fields[] = $field;
        $field->setWidget($this);

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CmsWidget
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
     * @return CmsWidget
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
