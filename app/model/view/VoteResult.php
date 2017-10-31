<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="vote_results")
 */
class VoteResult
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Proposal")
     * @ORM\JoinColumn(name="proposal_id", referencedColumnName="id")
     */
    private $proposalId;

    /**
     * @ORM\Column(type="integer", name="users_total")
     */
    private $usersTotal;

    /**
     * @ORM\Column(type="integer", name="positive")
     */
    private $positiveVotes;

    /**
     * @ORM\Column(type="integer", name="negative")
     */
    private $negativeVotes;

    /**
     * @ORM\Column(type="decimal", name="percents")
     */
    private $resultPercents;

    /**
     * @ORM\Column(type="decimal", name="voted")
     */
    private $usersPercent;

    /**
     * @ORM\Column(type="decimal",name="users_to_pass")
     */
    private $usersToPass;

    /**
     * @ORM\Column(type="decimal", name="percents_to_pass")
     */
    private $percentsToPass;

    /**
     * @return mixed
     */
    public function getProposalId()
    {
        return $this->proposalId;
    }

    /**
     * @param mixed $proposalId
     */
    public function setProposalId($proposalId)
    {
        $this->proposalId = $proposalId;
    }




    /**
     * @return mixed
     */
    public function getUsersTotal()
    {
        return $this->usersTotal;
    }

    /**
     * @param mixed $usersTotal
     */
    public function setUsersTotal($usersTotal)
    {
        $this->usersTotal = $usersTotal;
    }

    /**
     * @return mixed
     */
    public function getPositiveVotes()
    {
        return $this->positiveVotes;
    }

    /**
     * @param mixed $positiveVotes
     */
    public function setPositiveVotes($positiveVotes)
    {
        $this->positiveVotes = $positiveVotes;
    }

    /**
     * @return mixed
     */
    public function getNegativeVotes()
    {
        return $this->negativeVotes;
    }

    /**
     * @param mixed $negativeVotes
     */
    public function setNegativeVotes($negativeVotes)
    {
        $this->negativeVotes = $negativeVotes;
    }

    /**
     * @return mixed
     */
    public function getResultPercents()
    {
        return $this->resultPercents;
    }

    /**
     * @param mixed $resultPercents
     */
    public function setResultPercents($resultPercents)
    {
        $this->resultPercents = $resultPercents;
    }

    /**
     * @return mixed
     */
    public function getUsersPercent()
    {
        return $this->usersPercent;
    }

    /**
     * @param mixed $usersPercent
     */
    public function setUsersPercent($usersPercent)
    {
        $this->usersPercent = $usersPercent;
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

    public function getUnvoted()
    {
        return $this->usersTotal - $this->positiveVotes - $this->negativeVotes;
    }

}