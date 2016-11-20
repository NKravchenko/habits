<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoalNote
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GoalNoteRepository")
 * @ORM\Table(name="goal_note")
 */
class GoalNote
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="result_text", type="string", nullable=true)
     */
    private $resultText;

    /**
     * @ORM\Column(name="create_at", type="datetime", nullable=false)
     */
    private $createAt;

     /**
      * @ORM\ManyToOne(targetEntity="TextType", inversedBy="notes")
      * @ORM\JoinColumn(name="text_type", nullable=true)
      */
    private $valueTextType;

    /**
     * @ORM\Column(name="value_number", type="integer", nullable=true)
     */
    private $valueNumber;

    /**
     * @ORM\Column(name="value_time", type="integer", nullable=true)
     */
    private $valueTime;

    /**
     * @ORM\ManyToOne(targetEntity="Goal", inversedBy="notes")
     * @ORM\JoinColumn(name="goal_id", nullable=false)
     */
    private $goal;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getResultText()
    {
        return $this->resultText;
    }

    public function setResultText($resultText)
    {
        $this->resultText = $resultText;
    }

    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @param $createAt
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;
    }

    /**
     * @return mixed
     */
    public function getGoal()
    {
        return $this->goal;
    }

    /**
     * @param Goal $goal
     */
    public function setGoal(Goal $goal)
    {
        $this->goal = $goal;
    }

    /**
     * @return mixed
     */
    public function getValueTextType()
    {
        return $this->valueTextType;
    }

    /**
     * @param TextType $valueTextType
     */
    public function setValueTextType(TextType $valueTextType)
    {
        $this->valueTextType = $valueTextType;
    }

    /**
     * @return mixed
     */
    public function getValueNumber()
    {
        return $this->valueNumber;
    }

    /**
     * @param mixed $valueNumber
     */
    public function setValueNumber($valueNumber)
    {
        $this->valueNumber = $valueNumber;
    }

    /**
     * @return mixed
     */
    public function getValueTime()
    {
        return $this->valueTime;
    }

    /**
     * @param mixed $valueTime
     */
    public function setValueTime($valueTime)
    {
        $this->valueTime = $valueTime;
    }



}