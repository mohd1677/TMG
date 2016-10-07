<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class RoomsaverMapper extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $matrixProperty;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $roomsaverProperty;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $roomsaverCompany;

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
     * @return RoomsaverMapper
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getMatrixProperty()
    {
        return $this->matrixProperty;
    }

    /**
     * @param string $v
     *
     * @return RoomsaverMapper
     */
    public function setMatrixProperty($v)
    {
        $this->matrixProperty = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoomsaverProperty()
    {
        return $this->roomsaverProperty;
    }

    /**
     * @param string $v
     *
     * @return RoomsaverMapper
     */
    public function setRoomsaverProperty($v)
    {
        $this->roomsaverProperty = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoomsaverCompany()
    {
        return $this->roomsaverCompany;
    }

    /**
     * @param string $v
     *
     * @return RoomsaverMapper
     */
    public function setRoomsaverCompany($v)
    {
        $this->roomsaverCompany = $v;

        return $this;
    }
}
