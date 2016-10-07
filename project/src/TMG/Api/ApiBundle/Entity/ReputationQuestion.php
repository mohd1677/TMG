<?php

namespace TMG\Api\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReputationQuestion
 *
 * @ORM\Table(name="ReputationQuestions")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ReputationQuestion
{
    const OVER_ALL_RATING = "Over all rating";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="ReputationSurvey", inversedBy="questions")
     * @ORM\JoinColumn(name="survey", referencedColumnName="id")
     */
    private $survey;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="ReputationCategory", cascade={"persist"})
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="short_answer", type="string", nullable=true)
     */
    private $shortAnswer;

    /**
     * @var string
     *
     * @ORM\Column(name="long_answer", type="text", nullable=true)
     */
    private $longAnswer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set survey
     *
     * @param integer|ReputationSurvey $survey
     *
     * @return ReputationQuestion
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return integer
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set category
     *
     * @param integer|ReputationCategory $category
     *
     * @return ReputationQuestion
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set question
     *
     * @param string $question
     *
     * @return ReputationQuestion
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set short answer
     *
     * @param string $shortAnswer
     *
     * @return ReputationQuestion
     */
    public function setShortAnswer($shortAnswer)
    {
        $this->shortAnswer = $shortAnswer;

        return $this;
    }

    /**
     * Get short answer
     *
     * @return string
     */
    public function getShortAnswer()
    {
        return $this->shortAnswer;
    }

    /**
     * Set long answer
     *
     * @param string $longAnswer
     *
     * @return ReputationQuestion
     */
    public function setLongAnswer($longAnswer)
    {
        $this->longAnswer = $longAnswer;

        return $this;
    }

    /**
     * Get long longAnswer
     *
     * @return string
     */
    public function getLongAnswer()
    {
        return $this->longAnswer;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ReputationQuestion
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
     * @return ReputationQuestion
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
