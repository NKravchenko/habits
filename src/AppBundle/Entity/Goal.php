<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GoalRepository")
 * @ORM\Table(name="goals")
 */
class Goal
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
     * @Assert\NotBlank(message="Обязателно заполните название")
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "Описание должно быть не менее {{ limit }} символов",
     *      maxMessage = "Описание должно быть не более {{ limit }} символов"
     * )
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @Assert\Type(type="boolean")
     *
     * @ORM\Column(name="in_weekend", type="boolean")
     */
    private $inWeekend = true;

    /**
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @ORM\Column(name="date_stop", type="datetime", nullable=true)
     */
    private $dateStop;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $result;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="goals")
     * @ORM\JoinColumn(name="creator_id", nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="GoalType", inversedBy="goals")
     * @ORM\JoinColumn(name="goal_type", nullable=false)
     */
    private $goalType;

    /**
     * @ORM\OneToMany(targetEntity="GoalNote", mappedBy="goal")
     */
    private $notes;

    public function __construct()
    {
        $this->isActive = true;
        $this->result   = false;
        $this->notes    = new ArrayCollection();
        $this->creator  = new ArrayCollection();
        $this->dateAdd  = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getInWeekend()
    {
        return $this->inWeekend;
    }

    /**
     * @param mixed $inWeekend
     */
    public function setInWeekend($inWeekend)
    {
        $this->inWeekend = $inWeekend;
    }

    /**
     * @return mixed
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * @param \DateTime|null $dateAdd
     */
    public function setDateAdd(\DateTime $dateAdd)
    {
        $this->dateAdd = $dateAdd;
    }

    /**
     * @return mixed
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * @param mixed $dateUpdate
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;
    }

    /**
     * @return mixed
     */
    public function getDateStop()
    {
        return $this->dateStop;
    }

    /**
     * @param mixed $dateStop
     */
    public function setDateStop($dateStop)
    {
        $this->dateStop = $dateStop;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return ArrayCollection|GoalNote[]
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @return mixed
     */
    public function getGoalType()
    {
        return $this->goalType;
    }

    /**
     * @param GoalType $goalType
     */
    public function setGoalType(GoalType $goalType)
    {
        $this->goalType = $goalType;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param GoalType $goalType
     */
    public function setCreator(User $user)
    {
        $this->creator = $user;
    }

}

