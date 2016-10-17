<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class SalesRep extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $code;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Your name must be at least {{ limit }} characters.",
     *      maxMessage = "Your name cannot be longer than {{ limit }} characters."
     * )
     */
    protected $name;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->code;
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
     * @return SalesRep
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
     * @return SalesRep
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }
}
