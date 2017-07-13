<?php
/**
 * Created by PhpStorm.
 * User: MFormanek
 * Date: 15.06.2017
 * Time: 9:47
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
    public function saveOrUpdate($group)
    {
        $em = $this->getEntityManager();
        $em->persist($group);
        $em->flush();
    }

}