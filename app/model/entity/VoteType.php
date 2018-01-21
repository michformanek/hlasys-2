<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vote_type")
 */
class VoteType
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * Many VoteTypes have One Group.
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="voteTypes", fetch="EAGER")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\Column(type="decimal")
     */
    private $percentsToPass;

    /**
     * @ORM\Column(type="decimal")
     */
    private $usersToPass;


    /**
     * @ORM\Column(type="boolean")
     */
    private $active;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getPercentsToPass()
    {
        return $this->percentsToPass;
    }

    /**
     * @param mixed $percentsToPass
     */
    public function setPercentsToPass($percentsToPass)
    {
        $this->percentsToPass = $percentsToPass;
    }

    /**
     * @return mixed
     */
    public function getUsersToPass()
    {
        return $this->usersToPass;
    }

    /**
     * @param mixed $usersToPass
     */
    public function setUsersToPass($usersToPass)
    {
        $this->usersToPass = $usersToPass;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }


}