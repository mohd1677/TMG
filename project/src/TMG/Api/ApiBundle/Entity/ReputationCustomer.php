<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

/**
 * ReputationCustomer
 *
 * @ORM\Table(name="ReputationCustomers")
 * @ORM\Entity(repositoryClass="TMG\Api\ApiBundle\Entity\Repository\ReputationCustomerRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ReputationCustomer
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
     * @ORM\ManyToOne(targetEntity="Reputation", inversedBy="customers")
     * @ORM\JoinColumn(name="reputation_id", referencedColumnName="id")
     **/
    private $reputation;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checkout_date", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $checkoutDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent_date", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $sentDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     */
    private $redirect;

    /**
     * @var integer
     *
     * @ORM\Column(name="opened", type="integer")
     *
     * @Serializer\Expose
     */
    private $opened;

    /**
     * @var integer
     *
     * @ORM\Column(name="yes", type="integer")
     *
     * @Serializer\Expose
     */
    private $yes;

    /**
     * @var integer
     *
     * @ORM\Column(name="no", type="integer")
     *
     * @Serializer\Expose
     */
    private $no;

    /**
     * @var integer
     *
     * @ORM\Column(name="upload_date", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $uploadDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="email_id", type="integer", nullable=true)
     *
     * @Serializer\Expose
     */
    private $emailId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="follow_up_email_send_date", type="datetime", nullable=true)
     *
     */
    private $followUpEmailSendDate;

    /**
     * @var int
     *
     * @ORM\Column(name="follow_up_number_opened", type="integer", nullable=true)
     *
     */
    private $followUpNumberOpened;

    /**
     * @var int
     *
     * @ORM\Column(name="follow_up_yes", type="integer", nullable=true)
     *
     */
    private $followUpClickYes;

    /**
     * @var int
     *
     * @ORM\Column(name="follow_up_no", type="integer", nullable=true)
     *
     */
    private $followUpClickNo;

    /**
     * @var string
     *
     * @ORM\Column(name="follow_up_redirect_url", type="string", length=255, nullable=true)
     *
     */
    private $followUpRedirectUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="thank_you_email_send_date", type="datetime", nullable=true)
     *
     */
    private $thankYouEmailSendDate;

    /**
     * @var int
     *
     * @ORM\Column(name="thank_you_number_opened", type="integer", nullable=true)
     *
     */
    private $thankYouNumberOpened;

    /**
     * @var int
     *
     * @ORM\Column(name="thank_you_click_tripadvisor", type="integer", nullable=true)
     *
     */
    private $thankYouClickTripadvisor;

    /**
     * @var int
     *
     * @ORM\Column(name="thank_you_click_googleplus", type="integer", nullable=true)
     *
     */
    private $thankYouClickGoogleplus;

    /**
     * @var int
     *
     * @ORM\Column(name="thank_you_click_survey", type="integer", nullable=true)
     *
     */
    private $thankYouClickSurvey;

    /**
     * @var string
     *
     * @ORM\Column(name="thank_you_redirect_url", type="string", length=255, nullable=true)
     *
     */
    private $thankYouRedirectUrl;

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
     * Set reputation
     *
     * @param Reputation $reputation
     *
     * @return ReputationCustomer
     */
    public function setReputation(Reputation $reputation)
    {
        $this->reputation = $reputation;

        return $this;
    }

    /**
     * Get reputation
     *
     * @return Reputation
     */
    public function getReputation()
    {
        return $this->reputation;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ReputationCustomer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return ReputationCustomer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return ReputationCustomer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set checkoutDate
     *
     * @param \DateTime $checkoutDate
     *
     * @return ReputationCustomer
     */
    public function setCheckoutDate($checkoutDate)
    {
        $this->checkoutDate = $checkoutDate;

        return $this;
    }

    /**
     * Get checkoutDate
     *
     * @return \DateTime
     */
    public function getCheckoutDate()
    {
        return $this->checkoutDate;
    }

    /**
     * Set sentDate
     *
     * @param \DateTime $sentDate
     *
     * @return ReputationCustomer
     */
    public function setSentDate($sentDate)
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    /**
     * Get sentDate
     *
     * @return \DateTime
     */
    public function getSentDate()
    {
        return $this->sentDate;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return ReputationCustomer
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set redirect
     *
     * @param string $redirect
     *
     * @return ReputationCustomer
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Set email ID
     *
     * @param integer $emailId
     *
     * @return ReputationCustomer
     */
    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;

        return $this;
    }

    /**
     * Get redirect
     *
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * Get open
     *
     * @return integer
     */
    public function getOpened()
    {

    }

    /**
     * Set open
     *
     * @param integer $opened
     *
     * @return ReputationCustomer
     */
    public function setOpened($opened)
    {
        $this->opened = $opened;

        return $this;
    }

    /**
     * Get yes
     *
     * @return integer
     */
    public function getYes()
    {
        return $this->yes;
    }

    /**
     * Set yes
     *
     * @param integer $yes
     *
     * @return ReputationCustomer
     */
    public function setYes($yes)
    {
        $this->yes = $yes;

        return $this;
    }

    /**
     * Get no
     *
     * @return integer
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * Set no
     *
     * @param integer $no
     *
     * @return ReputationCustomer
     */
    public function setNo($no)
    {
        $this->no = $no;

        return $this;
    }

    /**
     * Get uploadDate
     *
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * Set uploadDate
     *
     * @param \DateTime $uploadDate
     *
     * @return ReputationCustomer
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ReputationCustomer
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ReputationCustomer
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get email ID
     *
     * @return integer
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     * Get follow up email sent date
     *
     * @return \DateTime
     */
    public function getFollowUpEmailSendDate()
    {
        return $this->followUpEmailSendDate;
    }

    /**
     * Set datetime follow up email was sent
     *
     * @param \DateTime $value
     * @return $this
     */
    public function setFollowUpEmailSendDate(\DateTime $value)
    {
        $this->followUpEmailSendDate = $value;

        return $this;
    }

    /**
     * Get the number of times the follow up email was opened
     *
     * @return int
     */
    public function getFollowUpNumberOpened()
    {
        return $this->followUpNumberOpened;
    }

    /**
     * Set the number of times the follow up email has been opened
     *
     * @param int $value
     * @return $this
     */
    public function setFollowUpNumberOpened($value)
    {
        $this->followUpNumberOpened = $value;

        return $this;
    }

    /**
     * Get the number of times yes has been clicked from the follow up email
     *
     * @return int
     */
    public function getFollowUpClickYes()
    {
        return $this->followUpClickYes;
    }

    /**
     * Set the number of times yes has been clicked from the follow up email
     *
     * @param int $value
     * @return $this
     */
    public function setFollowUpClickYes($value)
    {
        $this->followUpClickYes = $value;

        return $this;
    }

    /**
     * Get the number of times no has been clicked from the follow up email
     *
     * @return int
     */
    public function getFollowUpClickNo()
    {
        return $this->followUpClickNo;
    }

    /**
     * Set the number of times no has been clicked from the follow up email
     *
     * @param int $value
     * @return $this
     */
    public function setFollowUpClickNo($value)
    {
        $this->followUpClickNo = $value;

        return $this;
    }

    /**
     * Get the url the user was redirected to from the follow up email
     *
     * @return string
     */
    public function getFollowUpRedirectUrl()
    {
        return $this->followUpRedirectUrl;
    }

    /**
     * Set the url the user was redirected to from the follow up email
     *
     * @param string $value
     * @return $this
     */
    public function setFollowUpRedirectUrl($value)
    {
        $this->followUpRedirectUrl = $value;

        return $this;
    }

    /**
     * Get the datetime the thank you email was sent
     *
     * @return \DateTime
     */
    public function getThankYouEmailSendDate()
    {
        return $this->thankYouEmailSendDate;
    }

    /**
     * Set the datetime the thank you email was sent
     *
     * @param \DateTime $value
     * @return $this
     */
    public function setThankYouEmailSendDate(\DateTime $value)
    {
        $this->thankYouEmailSendDate = $value;

        return $this;
    }

    /**
     * Get the number of times the thank you email has been opened
     *
     * @return int
     */
    public function getThankYouNumberOpened()
    {
        return $this->thankYouNumberOpened;
    }

    /**
     * Set the number of times the thank you email has been opened
     *
     * @param int $value
     * @return $this
     */
    public function setThankYouNumberOpened($value)
    {
        $this->thankYouNumberOpened = $value;

        return $this;
    }

    /**
     * Get the number of times the user clicked tripadvisor from the thank you email
     *
     * @return int
     */
    public function getThankYouClickTripadvisor()
    {
        return $this->thankYouClickTripadvisor;
    }

    /**
     * Set the number of times the user clicked tripadvisor from the thank you email
     *
     * @param int $value
     * @return $this
     */
    public function setThankYouClickTripadvisor($value)
    {
        $this->thankYouClickTripadvisor = $value;

        return $this;
    }

    /**
     * Get the number of times the user clicked googleplus from the thank you email
     *
     * @return int
     */
    public function getThankYouClickGoogleplus()
    {
        return $this->thankYouClickGoogleplus;
    }

    /**
     * Set the number of times the user clicked googleplus from the thank you email
     *
     * @param int $value
     * @return $this
     */
    public function setThankYouClickGoogleplus($value)
    {
        $this->thankYouClickGoogleplus = $value;

        return $this;
    }

    /**
     * Get the number of times survey has been clicked from the thank you email
     *
     * @return int
     */
    public function getThankYouClickSurvey()
    {
        return $this->thankYouClickSurvey;
    }

    /**
     * Set the number of times survey has been clicked from the thank you email
     *
     * @param int $value
     * @return $this
     */
    public function setThankYouClickSurvey($value)
    {
        $this->thankYouClickSurvey = $value;

        return $this;
    }

    /**
     * Get the url that the thank you email redirected to
     *
     * @return string
     */
    public function getThankYouRedirectUrl()
    {
        return $this->thankYouRedirectUrl;
    }

    /**
     * Set the url that the thank you email redirected to
     *
     * @param string $value
     * @return $this
     */
    public function setThankYouRedirectUrl($value)
    {
        $this->thankYouRedirectUrl = $value;

        return $this;
    }

    /**
     * Update timestamps before persisting or updating records
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}
