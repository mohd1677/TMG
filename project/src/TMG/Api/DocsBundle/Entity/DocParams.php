<?php

namespace TMG\Api\DocsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocParams
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TMG\Api\DocsBundle\Entity\Repository\DocParamsRepository")
 */
class DocParams
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="example", type="string", length=255)
     */
    private $example;

    /**
     * @var boolean
     *
     * @ORM\Column(name="required", type="boolean")
     */
    private $required;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="\TMG\Api\DocsBundle\Entity\ApiDocMeta", inversedBy="params", cascade="persist")
     * @ORM\JoinColumn(name="route_id", referencedColumnName="id", onDelete="cascade")
     */
    private $route;



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
     * Set name
     *
     * @param string $name
     * @return DocParams
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
     * Set type
     *
     * @param string $type
     * @return DocParams
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set example
     *
     * @param string $example
     * @return DocParams
     */
    public function setExample($example)
    {
        $this->example = $example;

        return $this;
    }

    /**
     * Get example
     *
     * @return string
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * Set required
     *
     * @param boolean $required
     * @return DocParams
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return DocParams
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
     * Set route
     *
     * @param \TMG\Api\DocsBundle\Entity\ApiDocMeta $route
     * @return DocParams
     */
    public function setRoute(\TMG\Api\DocsBundle\Entity\ApiDocMeta $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \TMG\Api\DocsBundle\Entity\ApiDocMeta
     */
    public function getRoute()
    {
        return $this->route;
    }
}
