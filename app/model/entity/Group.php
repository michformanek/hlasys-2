<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * One Group has Many Vote Types.
     * @ORM\OneToMany(targetEntity="VoteType", mappedBy="group_id")
     */
    private $voteTypes;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getVoteTypes()
    {
        return $this->voteTypes;
    }

    /**
     * @param mixed $voteTypes
     */
    public function setVoteTypes($voteTypes)
    {
        $this->voteTypes = $voteTypes;
    }

}