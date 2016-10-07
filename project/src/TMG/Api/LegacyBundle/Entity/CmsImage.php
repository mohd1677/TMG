<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CmsImage extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $location;

    /**
     * @ORM\ManyToOne(targetEntity="CmsField", inversedBy="image")
     */
    protected $field;

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
     * @return CmsImage
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $value
     *
     * @return CmsImage
     */
    public function setLocation($value)
    {
        $this->location = $value;

        return $this;
    }

    /**
     * @return CmsField
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $value
     *
     * @return CmsImage
     */
    public function setField($value)
    {
        $this->field = $value;

        return $this;
    }
}
