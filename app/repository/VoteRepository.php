<?php
/**
 * Created by PhpStorm.
 * User: MFormanek
 * Date: 19.06.2017
 * Time: 8:17
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class VoteRepository extends EntityRepository
{

    public function saveOrUpdate($proposal)
    {
        $em = $this->getEntityManager();
        $em->persist($proposal);
        $em->flush();
    }

}