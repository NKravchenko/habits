<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TextType
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TextTypeRepository")
 * @ORM\Table(name="texttypes")
 */
class TextType
{
    /**
     * @var integer
     *
     * @ORM\Id
      * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(name="importance", type="integer", length=2, nullable=false)
     */
    private $importance;


    /**
     * @ORM\OneToMany(targetEntity="GoalNote", mappedBy="valueTextType")
     */
    private $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * @param mixed $importance
     */
    public function setImportance($importance)
    {
        $this->importance = $importance;
    }

    /**
     * @return  ArrayCollection|Goal[]
     */
    public function getNotes()
    {
        return $this->notes;
    }
}