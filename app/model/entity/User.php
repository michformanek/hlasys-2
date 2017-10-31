<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User
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
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * One Proposal has Many Comments.
     * @ORM\OneToMany(targetEntity="Watch", mappedBy="proposal")
     */
    private $watches;

    /**
     * One Group has Many Vote Types.
     * @ORM\OneToMany(targetEntity="User2Group", mappedBy="user_id")
     */
    private $user_group;

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
    public function getUserGroup()
    {
        return $this->user_group;
    }

    /**
     * @param mixed $user_group
     */
    public function setUserGroup($user_group)
    {
        $this->user_group = $user_group;
    }

}