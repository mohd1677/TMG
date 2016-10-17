<?php

namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="migrate_image_to_s3")
 * @ORM\Entity
 */
class MigrateImageToS3
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var  string
     *
     * @ORM\Column(name="type_code", type="string")
     */
    private $typeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="old_image_name", type="string", length=767)
     */
    private $oldImageName;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_number", type="integer")
     */
    private $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="large_image_url", type="string", length=767)
     */
    private $largeImageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="error_reason", type="text")
     */
    private $errorReason;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true)
     */
    private $isDefault;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set oldImageName
     *
     * @param string $oldImageName
     *
     * @return MigrateImageToS3
     */
    public function setOldImageName($oldImageName)
    {
        $this->oldImageName = $oldImageName;

        return $this;
    }

    /**
     * Get oldImageName
     *
     * @return string
     */
    public function getOldImageName()
    {
        return $this->oldImageName;
    }

    /**
     * Set accountNumber
     *
     * @param integer $accountNumber
     *
     * @return MigrateImageToS3
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return integer
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set largeImageUrl
     *
     * @param string $largeImageUrl
     *
     * @return MigrateImageToS3
     */
    public function setLargeImageUrl($largeImageUrl)
    {
        $this->largeImageUrl = $largeImageUrl;

        return $this;
    }

    /**
     * Get largeImageUrl
     *
     * @return string
     */
    public function getLargeImageUrl()
    {
        return $this->largeImageUrl;
    }

    /**
     * Set errorReason
     *
     * @param string $errorReason
     *
     * @return MigrateImageToS3
     */
    public function setErrorReason($errorReason)
    {
        $this->errorReason = $errorReason;

        return $this;
    }

    /**
     * Get errorReason
     *
     * @return string
     */
    public function getErrorReason()
    {
        return $this->errorReason;
    }

    /**
     * Set typeCode
     *
     * @param  string $typeCode
     *
     * @return  MigrateImageToS3
     */
    public function setTypeCode($typeCode)
    {
        $this->typeCode = $typeCode;

        return $this;
    }

    /**
     * Get typeCode
     *
     * @return  string
     */
    public function getTypeCode()
    {
        return $this->typeCode;
    }

    /**
     * Set isDefault
     *
     * @param  boolean $default
     *
     * @return  MigrateImageToS3
     */
    public function setIsDefault($default)
    {
        $this->isDefault = $default;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return  boolean
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }
}
