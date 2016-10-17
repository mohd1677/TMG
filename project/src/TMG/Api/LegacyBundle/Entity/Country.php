<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * **Internal use**
 * @ORM\Entity
 * @ORM\Table(name="country")
 */
class Country extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $code;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $value
     *
     * @return Country
     */
    public function setId($value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $v
     *
     * @return Country
     */
    public function setCode($v)
    {
        $this->code = $v;

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
     * @return Country
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }
}
