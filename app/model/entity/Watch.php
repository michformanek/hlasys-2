<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\WatchRepository")
 * @ORM\Table(name="watch")
 */
class Watch
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * Many Proposals have Same Status.
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Many Proposals have Same Status.
     * @ORM\ManyToOne(targetEntity="Proposal")
     * @ORM\JoinColumn(name="proposal_id", referencedColumnName="id")
     */
    private $proposal;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getProposal()
    {
        return $this->proposal;
    }

    /**
     * @param mixed $proposal
     */
    public function setProposal($proposal)
    {
        $this->proposal = $proposal;
    }


}