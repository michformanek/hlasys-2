<?php

namespace App\Service;



use App\Model\Group;
use Kdyby\Doctrine\EntityManager;

class GroupService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    private $groupRepository;


    /**
     * GroupService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->groupRepository = $entityManager->getRepository(Group::class);
    }

    public function findAll()
    {
        return $this->groupRepository->findAll();
    }

    public function findOne($id)
    {
        return $this->groupRepository->find($id);
    }

}