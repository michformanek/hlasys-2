<?php
/**
 * User: Michal Formánek
 * Date: 5.2.17
 * Time: 9:28
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProposalRepository")
 * @ORM\Table(name="proposals")
 */
class Proposal
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="date")
     */
    protected $dateEnd;

    /**
     * @ORM\Column(type="date")
     */
    protected $dateCreated;

    /**
     * Many Proposals have Same Author.
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * One Proposal has Many Items.
     * @ORM\OneToMany(targetEntity="Item", mappedBy="proposal")
     */
    protected $items;

    /**
     * One Proposal has Many Comments.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="proposal")
     */
    protected $comments;

    /**
     * One Proposal has Many Comments.
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="proposal")
     */
    protected $votes;

    /**
     * One Proposal has Many Comments.
     * @ORM\OneToMany(targetEntity="Log", mappedBy="proposal")
     */
    protected $logs;

    /**
     * One Proposal has Many Comments.
     * @ORM\OneToMany(targetEntity="Watch", mappedBy="proposal")
     */
    protected $watches;

    /**
     * Many Proposals have Same Status.
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * Many Proposals are supervised by Same Group.
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="responsible_group_id", referencedColumnName="id")
     */
    protected $responsibleGroup;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $trash;

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
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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
    public function getResponsibleGroup()
    {
        return $this->responsibleGroup;
    }

    /**
     * @param mixed $responsibleGroup
     */
    public function setResponsibleGroup($responsibleGroup)
    {
        $this->responsibleGroup = $responsibleGroup;
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

}