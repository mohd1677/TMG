<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ContractType extends AbstractEntity
{
    /**
     * Our 3-character internal product code, or the salesforce
     * product code if a 3 letter code does not exist.
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $code;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $description;

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
     * @return ContractType
     */
    public function setCode($v)
    {
        $this->code = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $v
     *
     * @return ContractType
     */
    public function setDescription($v)
    {
        $this->description = $v;

        return $this;
    }
}
