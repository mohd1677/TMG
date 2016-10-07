<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Amenity extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     **/
    protected $id;

    /**
     * Amenity nice name
     *
     * @ORM\Column(type="string", length=140, nullable=true)
     *
     * @Assert\Length(
     *      min = "2",
     *      max = "140",
     *      minMessage = "Amenity name must be at least {{ limit }} characters.",
     *      maxMessage = "Amenity name cannot be longer than {{ limit }} characters."
     * )
     */
    protected $name;

    /**
     * Amenity "key" ("Wireless Internet" -> "wifi_amenity")
     *
     * @ORM\Column(type="string", length=140, nullable=true)
     *
     * @Assert\Length(
     *      min = "2",
     *      max = "140",
     *      minMessage = "Amenity key selector must be at least {{ limit }} characters.",
     *      maxMessage = "Amenity key selector Cannot be longer than {{ limit }} characters."
     * )
     */
    protected $keySelector;

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
     * @return Amenity
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
     * @return Amenity
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeySelector()
    {
        return $this->keySelector;
    }

    /**
     * @param string $v
     *
     * @return Amenity
     */
    public function setKeySelector($v)
    {
        $this->keySelector = $v;

        return $this;
    }
}
