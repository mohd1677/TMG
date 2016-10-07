<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class GuideBook extends AbstractEntity
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
    protected $name;

    /**
     * Name of the guide in silverpop
     *
     * @ORM\Column(type="string")
     */
    protected $newsletterName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $pdf;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $v
     *
     * @return GuideBook
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
     * @return GuideBook
     */
    public function setName($v)
    {
        $this->name = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewsletterName()
    {
        return $this->newsletterName;
    }

    /**
     * @param string $v
     *
     * @return GuideBook
     */
    public function setNewsletterName($v)
    {
        $this->newsletterName = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param string $v
     *
     * @return GuideBook
     */
    public function setPdf($v)
    {
        $this->pdf = $v;

        return $this;
    }
}
