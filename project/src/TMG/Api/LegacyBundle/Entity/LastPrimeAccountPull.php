<?php
namespace TMG\Api\LegacyBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class LastPrimeAccountPull extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     **/
    protected $id;

    /**
     * @ORM\Column(type="string", length=40, unique=true)
     */
    protected $accountNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastPull;

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
     * @return LastPrimeAccountPull
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastPull()
    {
        return $this->lastPull;
    }

    /**
     * @param DateTime $v
     *
     * @return LastPrimeAccountPull
     */
    public function setLastPull(DateTime $v)
    {
        $this->lastPull = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string $v
     *
     * @return LastPrimeAccountPull
     */
    public function setAccountNumber($v)
    {
        $this->accountNumber = $v;

        return $this;
    }
}
