<?php
/**
 * User: Michal Formánek
 * Date: 5.2.17
 * Time: 9:53
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @ORM\Table(name="items")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Many Items have One Proposal.
     * @ORM\ManyToOne(targetEntity="Proposal", inversedBy="proposals")
     * @ORM\JoinColumn(name="proposal_id", referencedColumnName="id")
     */
    protected $proposal;

    /**
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $amount;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;
}