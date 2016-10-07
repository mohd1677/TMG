<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class SalesforceProduct extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $salesforceCode;

    /**
     * @ORM\ManyToOne(targetEntity="ContractType")
     * @ORM\JoinColumn(name="contract_type_code", referencedColumnName="code",  nullable=false)
     * @Assert\Valid
     */
    protected $contractType;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->salesforceCode;
    }

    /**
     * @return string
     */
    public function getSalesforceCode()
    {
        return $this->salesforceCode;
    }

    /**
     * @param string $v
     *
     * @return SalesforceProduct
     */
    public function setSalesforceCode($v)
    {
        $this->salesforceCode = $v;

        return $this;
    }

    /**
     * @return ContractType
     */
    public function getContractType()
    {
        return $this->contractType;
    }

    /**
     * @param ContractType $v
     *
     * @return SalesforceProduct
     */
    public function setContractType(ContractType $v)
    {
        $this->contractType = $v;

        return $this;
    }
}
