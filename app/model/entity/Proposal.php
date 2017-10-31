<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProposalRepository")
 * @ORM\Table(name="proposal")
 */
class Proposal
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="date")
     */
    private $dateStart;

    /**
     * Many Proposals have Same Author.
     * @ORM\ManyToOne(targetEntity="User",fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * One Proposal has Many Items.
     * @ORM\OneToMany(targetEntity="Item", mappedBy="proposal",cascade={"all"})
     */
    private $items;

    /**
     * One Proposal has Many Comments.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="proposal", cascade={"all"})
     */
    private $comments;

    /**
     * One Proposal has Many Votes.
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="proposal",cascade={"all"})
     */
    private $votes;

    /**
     * One Proposal has Many Logs.
     * @ORM\OneToMany(targetEntity="Log", mappedBy="proposal",cascade={"all"})
     */
    private $logs;

    /**
     * One Proposal has Many Comments.
     * @ORM\OneToMany(targetEntity="Watch", mappedBy="proposal",cascade={"persist"})
     */
    private $watches;

    /**
     * Many Proposals have Same Status.
     * @ORM\ManyToOne(targetEntity="Status",fetch="EAGER")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * Many Proposals are supervised by Same Group.
     * @ORM\ManyToOne(targetEntity="Group", fetch="EAGER")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * Many Proposals are supervised by Same Group.
     * @ORM\ManyToOne(targetEntity="VoteType",fetch="EAGER")
     * @ORM\JoinColumn(name="vote_type_id", referencedColumnName="id")
     */
    private $voteType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $trash;

    /**
     * @ORM\ManyToOne(targetEntity="VoteResult", fetch="EAGER")
     * @ORM\JoinColumn(name="id", referencedColumnName="proposal_id")
     */
    private $voteResult;

    /**
     * @return mixed
     */
    public function getVoteResult()
    {
        return $this->voteResult;
    }


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
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param mixed $dateEnd
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return mixed
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param mixed $dateStart
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
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
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param mixed $votes
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
    }

    /**
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param mixed $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    /**
     * @return mixed
     */
    public function getWatches()
    {
        return $this->watches;
    }

    /**
     * @param mixed $watches
     */
    public function setWatches($watches)
    {
        $this->watches = $watches;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
    public function getVoteType()
    {
        return $this->voteType;
    }

    /**
     * @param mixed $voteType
     */
    public function setVoteType($voteType)
    {
        $this->voteType = $voteType;
    }

    /**
     * @return mixed
     */
    public function getTrash()
    {
        return $this->trash;
    }

    /**
     * @param mixed $trash
     */
    public function setTrash($trash)
    {
        $this->trash = $trash;
    }

}