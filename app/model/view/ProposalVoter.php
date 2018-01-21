<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="voters_proposal")
 */
class ProposalVoter
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Proposal")
     * @ORM\JoinColumn(name="proposal_id", referencedColumnName="id")
     */
    private $proposal;


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getProposal()
    {
        return $this->proposal;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }



}