<?php
/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 5.2.17
 * Time: 23:09
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function saveOrUpdate($comment)
    {
        $em = $this->getEntityManager();
        $em->persist($comment);
        $em->flush();
    }
}
