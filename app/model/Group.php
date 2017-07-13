<?php
/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 5.2.17
 * Time: 17:11
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\Table(name="groups")
 */
class Group
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
    protected $name;

    /**
     * @ORM\Column(type="decimal", name="percentsToPass")
     */
    protected $percentsToPass;

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


}