<?php
namespace TMG\Api\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reputation_questions")
 */
class Question extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Rating from 0.5 to 5.0 in increments of 0.5
     * May be null if not required or answer is written
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $rating;

    /**
     * A rateable question, yes/no, or open-ended written question
     * ex: Please rate our follow-up after your recent service.
     * ex: Based on your experience, would you recommend <location>?
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $question;

    /**
     * Category of question, "Cleanliness", "Location", etc
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $category;

    /**
     * Yes/No, a written sentence/paragraph, or empty depending
     * on the question
     * @ORM\Column(type="text", nullable=true)
     */
    protected $answer;

    /**
     * @ORM\ManyToOne(targetEntity="Review", inversedBy="questions")
     */
    protected $review;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

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
     * @return Question
     */
    public function setId($v)
    {
        $this->id = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param string $v
     *
     * @return Question
     */
    public function setRating($v)
    {
        $this->rating = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $v
     *
     * @return Question
     */
    public function setQuestion($v)
    {
        $this->question = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $v
     *
     * @return Question
     */
    public function setCategory($v)
    {
        $this->category = $v;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param string $v
     *
     * @return Question
     */
    public function setAnswer($v)
    {
        $this->answer = $v;

        return $this;
    }

    /**
     * @return Review
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param Review $v
     *
     * @return Question
     */
    public function setReview(Review $v)
    {
        $this->review = $v;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Question
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Question
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

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
