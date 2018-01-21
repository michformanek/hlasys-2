<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements \JsonSerializable
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
     * @ORM\OneToMany(targetEntity="User2Group", mappedBy="user", fetch="EAGER")
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

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'name' => $this->getUsername()
        ];

    }
}