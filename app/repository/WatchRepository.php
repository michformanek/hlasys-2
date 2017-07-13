<?php
/**
 * Created by PhpStorm.
 * User: MFormanek
 * Date: 19.06.2017
 * Time: 8:59
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class WatchRepository extends EntityRepository
{
    public function saveOrUpdate($watch)
    {
        $em = $this->getEntityManager();
        $em->persist($watch);
        $em->flush();
    }

}