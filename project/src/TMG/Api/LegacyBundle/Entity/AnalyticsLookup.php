<?php

namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="analytics_lookup")
 */
class AnalyticsLookup extends AbstractEntity
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
    protected $propertyName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $propertyId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $value
     *
     * @return AnalyticsLookup
     */
    public function setId($value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @param string $v
     *
     * @return AnalyticsLookup
     */
    public function setPropertyName($v)
    {
        $this->propertyName = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyId()
    {
        return $this->propertyId;
    }

    /**
     * @param string $v
     *
     * @return AnalyticsLookup
     */
    public function setPropertyId($v)
    {
        $this->propertyId = $v;

        return $this;
    }
}
