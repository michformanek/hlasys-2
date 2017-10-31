<?php

namespace App\Service;


use App\Repository\GroupRepository;

class GroupService
{

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    public function __construct(
        GroupRepository $groupRepository
    )
    {
        $this->groupRepository = $groupRepository;
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